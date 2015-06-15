<?php

require_once "process.php";

$final = true;
$file  = 'oxigono1';
$file  = 'kittaro';

require_once $file.'_def.php';

curatePersons($file, $fakelos);

foreach ($procArr as $value)
{
    processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
}
