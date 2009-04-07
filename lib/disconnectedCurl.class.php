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
        CURLOPT_MAXREDIRS=>20,
        CURLOPT_NOSIGNAL=>true,
        CURLOPT_CONNECTTIMEOUT=>60,
        CURLOPT_LOW_SPEED_LIMIT=>1024,
        CURLOPT_LOW_SPEED_TIME=>300,
        CURLOPT_HEADER=>false,
        CURLOPT_RETURNTRANSFER=>false,
    );

    protected $running=null;
    protected $ch=null;
    protected $mh=null;
    protected $temp_name;
    protected $fp;
    protected $temp_name_h;
    protected $fp_h;
    protected $headers=null;
    protected $info;
    protected $status_line;
    protected $hash_context;

    function __construct($url,$options=array())
    {
        register_shutdown_function(array(&$this, "__destruct"));
        $ch=curl_init($url);

        $this->temp_name=$this->generateTempName();
        $this->temp_name_h=$this->generateTempName();

        $options=$this->curl_options + $options;

        $this->fp=fopen($this->temp_name,'w');
        if(!$this->fp) throw new Exception('Tempfile not writeable');
        if($this->canHash())
        {
            $this->hash_context=hash_init('sha1');
            if(!$this->hash_context)
                throw new Exception('Hash context creation failed');
            $options[CURLOPT_WRITEFUNCTION]=Array($this,'write');
        }
        else
        {
            // need to hash in some other way here
            throw new Exception('Hash context unavailable');
            $options[CURLOPT_FILE]=$this->fp;
        }

        $this->fp_h=fopen($this->temp_name_h,'w+');
        if(!$this->fp_h) throw new Exception('Tempfile not writeable');
        $options[CURLOPT_WRITEHEADER]=$this->fp_h;

        curl_setopt_array($ch,$options);

        $mh=curl_multi_init();
        curl_multi_add_handle($mh,$ch);
        $this->ch=$ch;
        $this->mh=$mh;
    }

    protected function generateTempName()
    {
        return tempnam(sys_get_temp_dir(),'dcurl');
    }

    public function isRunning()
    {
        return $this->running;
    }

    public function run($lambda=null)
    {
        do {
            $ret = curl_multi_exec($this->mh,$this->running);
            $e=curl_error($this->ch);
            if($e)
                throw new Exception("ERROR $e\n");
            $this->info=curl_getinfo($this->ch);
            if($lambda)
                call_user_func($lambda,$this,false);
        } while($this->running>0);
       

        
        if($lambda)
            $ret =  call_user_func($lambda,$this,true);


        rewind($this->fp_h);
        $lines = explode("\r\n",stream_get_contents($this->fp_h));
        $this->status_line=array_shift($lines); // get rid of http status code line
        $this->headers=Array();
        foreach($lines as $line)
        {
            if($line!='')
            {
                @list($k,$v)=@explode(': ',$line,2);
                if($k && $v)
                    $this->headers[$k]=$v;
            }
        }

        curl_multi_remove_handle($this->mh, $this->ch);
        curl_multi_close($this->mh);
        fclose($this->fp);
        fclose($this->fp_h);

        if($this->info['http_code']!=200)
            throw new Exception("Curl status code not 200; line follows:\n".$this->status_line);

        return $ret;
    }

    public function getFile()
    {
        if($this->isRunning())
            return null;
        return $this->temp_name;
    }
    function __destruct()
    {
      if(file_exists($this->temp_name))
        unlink($this->temp_name);
      if(file_exists($this->temp_name_h))
        unlink($this->temp_name_h);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        return @$this->headers[$name];
    }

    public function getInfo()
    {
        return $this->info;
    }

    protected function canHash()
    {
        return function_exists('hash_init');
    }

    public function write($curl_resource,$data)
    {
        hash_update($this->hash_context,$data);
        return fwrite($this->fp,$data);
    }

    public function getSha1()
    {
       if($this->isRunning()==FALSE)
       {
        if($this->canHash())
        {
            return hash_final($this->hash_context);
        }
        else
        {
            return sha1_file($this->temp_name); // fallback approach
        }
       }
       return null; // xfer not finished
    }
}
