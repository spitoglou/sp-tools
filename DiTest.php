<?php
require_once "config/config.inc";
use Sptools\Di;


class DiTest extends \PHPUnit_Framework_TestCase {
    public function testHello() {
        $a=1;
        $b=1;
        $this->assertEquals($a,$b);
    }
}