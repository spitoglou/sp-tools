<?php
$fakelos = 2;

$sk = 75;
$ther = 73;
$epan = 74;
$fu = 241;

$procArr = array();
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Αποφυλάκιση" => "release", 
        "Μεταγωγή" => "transport", 
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή εκτός ΚΕΘΕΑ" => "witdoutout", 
        "Ολοκλήρωση" => "completion",
        "" => "open",
    ));