<?php
$fakelos = 2;

$sk = 51;
$ther = 53;
$ther2 = 59;
$epan = 60;

$procArr = array();
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer", 
        "Ολοκλήρωση" => "transfer",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$ther, 
    "unit2"=>$ther2, 
    "event"=>array(
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εντός ΚΕΘΕΑ"=>"transfer", 
        "Ολοκλήρωση" => "transfer", 
        "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "Αποφυλάκιση" => "release",
        "Μεταγωγή" => "transport",
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$ther2, 
    "unit2"=>$epan, 
    "event"=>array(
        "Διακοπή" => "withdrawal", 
        "Ολοκλήρωση" => "transfer", 
        "Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin",
        "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "Αποφυλάκιση" => "release",
        "Μεταγωγή" => "transport",
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>19, 
    "unit1"=>$epan, 
    "unit2"=>$epan, 
    "event"=>array(
        "Ολοκλήρωση" => "completion", 
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout",
         "Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin",
         "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "" => "open",
     ));