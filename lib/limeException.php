<?php
/*
  limeException 
*/
class limeException extends sfException
{
    protected $token;
    static public function createFromException($token,Exception $e)
    {
        $exception = new limeException($token,(sprintf('Wrapped %s: %s', get_class($e), $e->getMessage())));
        $exception->setWrappedException($e);

        return $exception;
    }
    
    function __construct(string $token,string $message)
    {
        parent::__construct($message);
        $this->token=$token;
    }
}
