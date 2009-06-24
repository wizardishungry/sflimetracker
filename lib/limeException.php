<?php
/*
  limeException 
*/
class limeException extends sfException
{
    const HELP_BASE="http://limecast.com/tracker/help/"; // should this be in GH pages?
    const HELP_HOME="http://limecast.com/tracker";
    protected $token;
    static public function createFromException(Exception $e,$token=null)
    {
        $exception = new limeException($token,(sprintf('Wrapped %s: %s', get_class($e), $e->getMessage())));
        $exception->setWrappedException($e);

        return $exception;
    }
    
    function __construct($token,$message)
    {
        $this->token=@$token;
        parent::__construct($message. (@$token?" - [$token]\n":''));
    }

    public function getUrl()
    {
        if(@$this->token)
            $url=self::HELP_BASE.$this->token;
        else
            $url=self::HELP_HOME;

        return $url;
    }
}
