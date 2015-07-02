<?php
$fakelos = 2;
$extended=true;


$sk = 78;
$ther = 73;
$epan = 74;
$fu = 241;

$procArr = array();
$procArr[] = array(
    "offset"=>10, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Αποφυλάκιση" => "release", 
        "Μεταγωγή" => "transport", 
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή εκτός ΚΕΘΕΑ" => "witdoutout", 
        "" => "open",
    ));