<?php
$fakelos = 2;

$sk = '';
$ther = 238;
$epan = 232;
$fu = 234;

$procArr = array();
/*$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή" => "witdoutout", 
        "Ολοκλήρωση1" => "completion", 
        "" => "open",
    ));*/
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$ther, 
    "unit2"=>$epan, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ" => "witdoutin", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$epan, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΟΛΟΚΛΗΡΩΣΗ " => "transfer",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$fu, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "graduation", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
     ));