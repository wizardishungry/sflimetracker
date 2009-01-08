<?php
    /*
        Common behaviors to keep stuff shorthand
        This isn't actually a propel behavior per-say because it was refactored
        to get around the stupid way static fields/methods are handled.
         -- "Unfortunately, as of PHP 5, static method calls cannot be caught
        by a __call()."
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
