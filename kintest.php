<?php

require_once "vendor/autoload.php";

d($_SERVER);
d(1);
// s($GLOBALS);
d(microtime());
sleep(2);
d(microtime());
Kint::trace();
throw new Exception("Error Processing Request", 1);


echo "end";