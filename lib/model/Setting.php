<?php

class Setting extends BaseSetting
{
    public function reset()
    {
        return $this->setValue($this->getVendor());
    }
}
