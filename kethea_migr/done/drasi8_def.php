<?php
$fakelos = 2;
$extended=true;

$sk = 58;
$ther = 59;
$ther2 = 59;
$epan = 60;

$procArr = array();
$procArr[] = array(
    "offset"=>10, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "Ολοκλήρωση" => "transfer",
        "ΠΑΡΑΠΟΜΠΗ"=>"transfer", 
        "ΠΑΡΑΠΟΜΠΗ "=>"transfer", 
        "ΑΠΟΦΥΛΑΚΙΣΗ" => "release",
        "ΜΕΤΑΓΩΓΗ" => "transport",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>14, 
    "unit1"=>$ther, 
    "unit2"=>$ther2, 
    "event"=>array(
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ"=>"transfer", 
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "ΑΠΟΦΥΛΑΚΙΣΗ" => "release",
        "ΜΕΤΑΓΩΓΗ" => "transport",
        "" => "open",
    ));
/*$procArr[] = array(
    "offset"=>18, 
    "unit1"=>$ther2, 
    "unit2"=>$epan, 
    "event"=>array(
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin",
        "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "ΑΠΟΦΥΛΑΚΙΣΗ" => "release",
        "ΜΕΤΑΓΩΓΗ" => "transport",
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>22, 
    "unit1"=>$epan, 
    "unit2"=>$epan, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "completion", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "Παραπομπή εκτός ΚΕΘΕΑ"=>"witdoutout",
         "Παραπομπή εντός ΚΕΘΕΑ"=>"witdoutin",
         "Παραπομπή εντός ΚΕΘΕΑ1"=>"witdoutin",
        "" => "open",
     ));*/