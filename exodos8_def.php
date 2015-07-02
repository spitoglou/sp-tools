<?php
$fakelos = 2;
$extended=true;

$sk = 72;
$ther = 73;
$epan = 74;
$fu = 241;

$procArr = array();
$procArr[] = array(
    "offset"=>10, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer", 
        "Ολοκλήρωσε" => "transfer", 
        "Ολοκλήρωσε " => "transfer", 
        "Ολοκλήρωση" => "transfer",
        "ολοκλήρωση" => "transfer",
        "Διακοπή" => "withdrawal", 
        "Παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή εντός ΚΕΘΕΑ " => "witdoutin", 
        "παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή εντός" => "witdoutin", 
        "Παραπομπή εκτός" => "witdoutout", 
        "Παραπομπή εκτός ΚΕΘΕΑ" => "witdoutout", 
        "Παραπομπής εκτός ΚΕΘΕΑ" => "witdoutout", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>14, 
    "unit1"=>$ther, 
    "unit2"=>$epan, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer",
        "Ολολήρωση" => "transfer",
        "ολοκλήρωση" => "transfer",
        "Ολοκλήρωση " => "transfer",
        "Ολοκλήρωση  " => "transfer",
        "Διακοπή" => "withdrawal", 
        "Διακοπή " => "withdrawal", 
        "διακοπή" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ ΕΝΤΟΣ ΚΕΘΕΑ" => "witdoutin", 
        "παραπομπή εντός" => "witdoutin", 
        "Παραπομπή Εντός" => "witdoutin", 
        "Παραπομπή εντός ΚΕΘΕΑ" => "witdoutin", 
        "Παραπομπή εκτός ΚΕΘΕΑ" => "witdoutout", 
        "Παραπομπή εκτός" => "witdoutout", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));