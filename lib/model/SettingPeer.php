<?php

class SettingPeer extends BaseSettingPeer
{
    public static function retrieveByKey($key, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(SettingPeer::KEY,$key);
        return SettingPeer::doSelectOne($c, $con);
    }

    public static function resetByKey($key, PropelPDO $con = null)
    {
        $o=self::retrieveByKey($key,$con);
        if($o)
        {
            $o->reset();
            return $o->save($con);
        }
        throw new sfException('key not found');
    }

    public static function reset(Criteria $criteria =null, PropelPDO $con=null)
    {
        $os=self::doSelect($criteria, $con);
        foreach($os as $o)
        {
            $o->reset();
            $o->save();
        }
        return $os;
    }
}
