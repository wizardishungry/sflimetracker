<?php
/*
  limeException 
*/
class limeException extends sfException
{
    const HELP_BASE="http://wiki.github.com/WIZARDISHUNGRY/sflimetracker/exception-"; // should this be in GH pages?
    const HELP_HOME="http://limecast.com/tracker";
    protected $token;
    static public function createFromException($token,Exception $e)
    {
        $exception = new limeException($token,(sprintf('Wrapped %s: %s', get_class($e), $e->getMessage())));
        $exception->setWrappedException($e);

        return $exception;
    }
    
    function __construct(string $token,string $message)
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
