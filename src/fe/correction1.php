<?php
/**
     * correction after bug of not creating transports and releases
     * due to forgotten break; statements
     */

require_once 'config.inc';
echo '<pre>';

$sql    = "SELECT * FROM FE_PROD.FE_PERSON_EVENTS where PEVN_COMMENTS like '%Αποφυλάκισης'";
$sql    = "SELECT * FROM FE_PROD.FE_PERSON_EVENTS where PEVN_COMMENTS like '%Μεταγωγής'";
$result = $db->get_results($sql, ARRAY_A);

foreach ($result as $key => $value) {
    $sql = "INSERT INTO FE_PROD.FE_TRANSPORTS (";
    $sql .= "TRAP_ID,";
    $sql .= "TRAP_PEVN_ID,";
    $sql .= "USER_CREATE,";
    $sql .= "DATE_CREATE,";
    $sql .= "DATE_UPDATE";
    $sql .= ") VALUES (";
    $sql .= "FE_PROD.SEQ_FE_TRANSPORTS_TRAP_ID.NEXTVAL,";
    $sql .= "{$value['PEVN_ID']},";
    $sql .= "1,";
    $sql .= formatDateForSQL($value['PEVN_DATE']).",";
    $sql .= formatDateForSQL($value['PEVN_DATE']);
    $sql .= ")";
    print_r($value);
    print_r($sql);
    $db->query($sql);
}
