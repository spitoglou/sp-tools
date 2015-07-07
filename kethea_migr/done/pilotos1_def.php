<?php
$fakelos = 2;

$sk = 156;
$ther = 158;
$epan = 161;
$fu = 242;

$procArr = array();
$procArr[] = array(
    "offset"=>7, 
    "unit1"=>$sk, 
    "unit2"=>$ther, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ (ΘΚ ΕΞΟΔΟΣ)" => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ (ΘΚ ΕΞΟΔΟΣ)" => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ" => "witdoutin", 
        "ΠΑΡΑΠΟΜΠΗ " => "witdoutin", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>11, 
    "unit1"=>$ther, 
    "unit2"=>$epan, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "ΠΑΡΑΠΟΜΠΗ" => "witdoutin", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>15, 
    "unit1"=>$epan, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
    ));
$procArr[] = array(
    "offset"=>19, 
    "unit1"=>$fu, 
    "unit2"=>$fu, 
    "event"=>array(
        "ΑΠΟΦΟΙΤΗΣΗ" => "graduation", 
        "ΔΙΑΚΟΠΗ" => "withdrawal", 
        "" => "open",
     ));