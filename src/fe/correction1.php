<?php
/**
     * correction after bug of not creating transports and releases
     * due to forgotten break; statements
     */

require_once 'config/config.inc';
echo '<pre>';

$sql    = "SELECT * FROM FE_PROD.FE_PERSON_EVENTS where PEVN_COMMENTS like '%Αποφυλάκισης'";
$sql    = "SELECT * FROM FE_PROD.FE_PERSON_EVENTS where PEVN_COMMENTS like '%Μεταγωγής'";
$result = $db->get_results($sql, ARRAY_A);

foreach ($result as $key => $value) {
    $date = formatDateForSQL($value['PEVN_DATE']);
    $sql = "INSERT INTO FE_PROD.FE_TRANSPORTS (
            TRAP_ID,TRAP_PEVN_ID,USER_CREATE,DATE_CREATE,DATE_UPDATE) VALUES
            (FE_PROD.SEQ_FE_TRANSPORTS_TRAP_ID.NEXTVAL,
            {$value['PEVN_ID']},
            1,
            {$date},
            {$date})";
    print_r($value);
    print_r($sql);
    $db->query($sql);
}
