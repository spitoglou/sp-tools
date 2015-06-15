<?php
$fakelos = 2;

$sk=131;
$ther=132;
$epan=133;
$fu=213;

$procArr=array();
$procArr[]=array("offset"=>7,"unit1"=>$sk,"unit2"=>$ther,"event"=>array("Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>11,"unit1"=>$ther,"unit2"=>$epan,"event"=>array("Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>15,"unit1"=>$epan,"unit2"=>$fu,"event"=>array("Ολοκλήρωση" => "transfer", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));
$procArr[]=array("offset"=>19,"unit1"=>$fu,"unit2"=>$fu,"event"=>array("Ολοκλήρωση" => "graduation", "Διακοπή" => "withdrawal", "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout","Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin"));