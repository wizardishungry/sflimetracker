<?php
/*
    Syntax of this file is invalid in PHP4 so the inclusion is
    done after the version test.
    "PHP 5 is alive!"
*/

$configuration = ProjectConfiguration::getApplicationConfiguration($app,$env,$debug);
sfContext::createInstance($configuration)->dispatch();
