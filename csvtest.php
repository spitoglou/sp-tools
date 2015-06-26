<?php

require_once "process.php";

$final = true;
$extended = false;
$file  = 'ithaki8';

if ($_GET[update]) {
    updatePersonExtended($file,2);
} else {
    require_once $file.'_def.php';

    curatePersons($file, $fakelos, $extended);

    foreach ($procArr as $value)
    {
        processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
    }
}
