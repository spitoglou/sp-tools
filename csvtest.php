<?php

require_once "process.php";

$final = true;
$extended = false;
$file  = 'kittaro';
$file  = 'oxigono1';
$file  = 'oxigono2';
$file  = 'oxigono3';
$file  = 'oxigono4';
$file  = 'kittaro2';

require_once $file.'_def.php';

curatePersons($file, $fakelos, $extended);

foreach ($procArr as $value)
{
    processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
}
