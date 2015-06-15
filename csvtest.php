<?php

require_once "process.php";

$final   = true;
$file    = 'oxigono1';
$file    = 'kittaro';

// TODO : make an array of arguments for multiple passes in one run
/*$offset = 7;
$unit1  = 1;
$unit2  = 2;
$event  = array("ΟΛΟΚΛΗΡΩΣΗ" => "transfer", "ΔΙΑΚΟΠΗ" => "withdrawal", "" => "open");*/

require_once $file.'_def.php';

/*$procArr=array();
$procArr[]=array("offset"=>7,"unit1"=>112,"unit2"=>113,"event"=>array("ΔΙΑΚΟΠΗ" => "withdrawal", ));*/

curatePersons($file, $fakelos);

foreach ($procArr as $value) {
	//processFile($file, $fakelos, $value['offset'], $value['unit1'], $value['unit2'], $value['event']);
}

//processFile($file, $fakelos, $offset, $unit1, $unit2, $event);
