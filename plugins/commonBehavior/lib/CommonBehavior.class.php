<?php
    /*
        Common behaviors to keep stuff shorthand
    */
class CommonBehavior
{
    public static function retrieveBySlug($class,$slug)
    {
        $c = new Criteria();

        // $class::FIELD is php 5.3.0+ only, let's hack around that
        $col_name=call_user_func(Array($class,'translateFieldname'),'slug', BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);

        $c->add($col_name,$slug);
        return call_user_func(Array($class,'doSelectOne'),$c);
    }
}
?>
