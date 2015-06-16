<?php
$fakelos = 2;
$extended=true;

$sk=131;
$ther=132;
$epan=133;
$fu=219;

$procArr=array();
$procArr[]=array("offset"=>10,"unit1"=>$sk,"unit2"=>$ther,"event"=>array(""=>"open","Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>14,"unit1"=>$ther,"unit2"=>$epan,"event"=>array(""=>"open","Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>18,"unit1"=>$epan,"unit2"=>$fu,"event"=>array(""=>"open","Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>22,"unit1"=>$fu,"unit2"=>$fu,"event"=>array(""=>"open","Ολοκλήρωση" => "graduation", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));