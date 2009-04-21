<?php 
class myWebResponse extends sfWebResponse
{

    public function getTitle()
    {
        $parts = array();
        if(parent::getTitle())
            $parts[]=parent::getTitle();
        $parts[]=sfConfig::get('app_version_name');
        return implode(' - ',$parts);
    }

}
