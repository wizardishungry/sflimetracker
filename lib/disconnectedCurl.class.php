<?php
/*
    Wrapper class for curl_multi_*
    asynchonous browser object
    data serialied to a tempfile
*/
class disconnectedCurl
{
    protected $curl_options=Array(
        CURLOPT_BINARYTRANSFER=>true,
        CURLOPT_FILETIME=>true,
        CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_HEADER=>false,
    );

    protected $running=null;
    protected $ch=null;
    protected $mh=null;
    protected $temp_name;

    function __construct($url,$options=array())
    {
        $ch=curl_init($url);

        $this->temp_name=tempnam(sys_get_temp_dir(),sfConfig::get('app_version_name')); // todo do we need to remove symfonyism?

        $options=array_merge($this->curl_options,$options);
        $options[CURLOPT_FILE]=$this->temp_name;

        curl_setopt_array($ch,$options);

        $mh=curl_multi_init();
        curl_multi_add_handle($ch);
        $this->ch=$ch;
        $this->mh=$mh;
    }

    public function isRunning()
    {
        return $this->running;
    }

    public function run($lambda)
    {
        do {
            curl_multi_exec($this->mh,$this->running);
            if($lambda)
                call_user_func($lambda,$this);
        } while($this->running>0);
        
        $ret =  call_user_func($lambda,$this);
        curl_multi_remove_handle($this->mh, $this->ch);
        curl_multi_close($this->mh);
        return $ret;
    }

    public function getFile()
    {
        if($this->isRunning())
            return null;
        return $this->temp_name;
    }
}
