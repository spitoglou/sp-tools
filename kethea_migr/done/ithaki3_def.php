<?php
$fakelos = 2;

$sk = 90;
$ther = 91;
$epan = 92;
$fu = 236;

$procArr = array();
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer", 
        "Ολοκλήρωση" => "transfer",
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$ther, 
    "unit2"=>$epan, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer",
        "Διακοπή" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ" => "witdoutin", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$epan, 
    "unit2"=>$fu, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer",
        "Διακοπή" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>19, 
    "unit1"=>$fu, 
    "unit2"=>$fu, 
    "event"=>array(
        "Ολοκλήρωση" => "graduation", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
     ));