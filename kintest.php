<?php
/**
 	* file to test kint debugger
 	* @author Stavros Pitoglou <spitoglou@gmail.com>	
 	*/

require_once "vendor/autoload.php";

d($_SERVER);
d(1);
d(microtime());
sleep(2);
d(microtime());
Kint::trace();

echo "end";