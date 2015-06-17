<?php

require_once "config.inc";
require_once "vendor/simpletest/simpletest/autorun.php";

class PersonLoadTest extends UnitTestCase
{
    function testPersonLoad()
    {
        global $app;
        $pers= new Person($app,3427);
        $this->assertEqual($pers->data['PERS_ID'],3427);
    }
}

echo 'ok';