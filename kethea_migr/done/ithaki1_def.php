<?php
$fakelos = 2;

$sk = '';
$ther = 239;
$epan = 233;
$fu = 235;

$procArr = array();
/*$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "Ολοκλήρωση" => "transfer", 
        "Ολοκλήρωση" => "transfer",
    ));*/
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$ther, 
    "unit2"=>$epan, 
    "event"=>array(
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$epan, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$fu, 
    "unit2"=>$fu, 
    "event"=>array(
        "Ολοκλήρωση" => "graduation", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
     ));