<?php

require_once "src/fe/process.php";

$final = true;
$extended = false;
$file  = 'exelixisex';

if ($_GET[update]) {

    updatePersonExtended($file,2);
    
} else {
    require_once $file.'_def.php';

    curatePersons($file, $fakelos, $extended);

    ob_start();
    foreach ($procArr as $value)
    {
        processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
        ob_flush();
    }
    ob_end_clean();
}
