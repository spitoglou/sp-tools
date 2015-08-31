<?php
$fakelos = 2;
$extended = true;

$sk = 52;
$ther = 59;
$ther2 = 59;
$epan = 60;

$procArr = array();
$procArr[] = array(
    "offset" => 10,
    "unit1" => $sk,
    "unit2" => $ther,
    "event" => array(
        "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
        "Ολοκλήρωση" => "transfer",
        "ΠΑΡΑΠΟΜΠΗ" => "transfer",
        "ΠΑΡΑΠΟΜΠΗ " => "transfer",
        "ΑΠΟΦΥΛΑΚΙΣΗ" => "release",
        "ΜΕΤΑΓΩΓΗ" => "transport",
        "ΔΙΑΚΟΠΗ" => "withdrawal",
        "" => "open",
    )
);
