<?php
/**
    * file to test kint debugger
    * @author Stavros Pitoglou <spitoglou@gmail.com>
    */

d($_SERVER);
d(1);
d(microtime());
sleep(2);
d(microtime());
Kint::trace();

echo 'End of kintest script!';
