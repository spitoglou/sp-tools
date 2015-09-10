<?php

require_once "src/fe/process.php";

$final = true;
$extended = false;
$file = 'test';

if ($_GET['update']) {
    updatePersonExtended($file, 2);
} else {
    require_once 'kethea_migr/' . $file . '_def.php';

    curatePersons($file, $fakelos, $extended);

    ob_start();
    foreach ($procArr as $value) {
        processFile($file, $fakelos, $value['offset'], $value['event'], $units);
        ob_flush();
    }
    ob_end_clean();
}
