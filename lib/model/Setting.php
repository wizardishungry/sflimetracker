<?php

class Setting extends BaseSetting
{
    public function reset()
    {
        return $this->setValue(null);
    }
    public function getValue()
    {
        return parent::getValue()?parent::getValue():$this->getVendor();
    }
}
