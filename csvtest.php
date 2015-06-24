<?php

require_once "process.php";

$final = true;
$extended = false;
// $file  = 'kittaro';
// $file  = 'oxigono1';
// $file  = 'oxigono2';
// $file  = 'oxigono3';
// $file  = 'oxigono4';
// $file  = 'kittaro2';
// $file  = 'exelixis1';
// $file  = 'exelixis2';
// $file  = 'diavasi1';
// $file  = 'diavasi2';
// $file  = 'exelixis3';
$file  = 'test';

require_once $file.'_def.php';

curatePersons($file, $fakelos, $extended);

foreach ($procArr as $value)
{
    processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
}
