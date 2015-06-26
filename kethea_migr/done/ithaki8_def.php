<?php
$fakelos = 2;

$sk = '';
$ther = 84;
$epan = 85;
$fu = 229;

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
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ." => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ " => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ" => "witdoutout", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΑΓΝΩΣΤΟ" => "withdrawal", 
        "" => "open",
        " " => "open",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$epan, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΟΛΟΚΛΗΡΩΣΗ " => "transfer",
        " ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΑΓΝΩΣΤΟ" => "withdrawal", 
        "" => "open",
        " " => "open",
        "ΣΥΝΕΧΙΖΕΙ" => "open",
        "ΠΑΡΑΠΟΜΠΗ ΚΕΠΟΠΑ" => "witdoutout", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ" => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ " => "witdoutin", 
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$fu, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "graduation", 
        "ΑΠΟΦΟΙΤΗΣΗ" => "graduation", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΑΓΝΩΣΤΟ" => "withdrawal", 
        "" => "open",
        " " => "open",
     ));