<?php
require_once 'config.inc';
$queue=array();
//$queue[]=array('deleteUnit',UNIT_ID);
//($queue[]=array('insertUser','username','salt','FullName','unit1|unit2|...','1 : admin')

// $queue[]=array('insertUser','gzaxaro','7890','ΓΙΑΝΝΗΣ ΖΑΧΑΡΟΠΟΥΛΟΣ',,1);
// $queue[]=array('insertUser','kkonidari','3545','ΚΑΤΕΡΙΝΑ ΚΟΝΙΔΑΡΗ',,0);
// $queue[]=array('insertUser','mmilonas','6905','ΜΙΧΑΛΗΣ ΜΥΛΩΝΑΣ',,0);
// $queue[]=array('insertUser','isimeonidou','4607','ΙΩΑΝΝΑ ΣΥΜΕΩΝΙΔΟΥ',,0);
// $queue[]=array('insertUser','thleman','6682','ΘΑΛΕΙΑ ΛΕΜΑΝ',,0);
// $queue[]=array('insertUser','pvenizelou','9035','ΠΗΝΕΛΟΠΗ ΒΕΝΙΖΕΛΟΥ',,0);
// $queue[]=array('insertUser','dkordatos','4755','ΔΗΜΗΤΡΗΣ ΚΟΡΔΑΤΟΣ',,0);
// $queue[]=array('insertUser','xkoutsour','6683','ΧΡΙΣΤΙΝΑ ΚΟΥΤΣΟΥΡΙΔΟΥ',,0);
// $queue[]=array('insertUser','pansaivan','7122','ΠΑΝΑΓΙΩΤΗΣ ΣΑΙΒΑΝΙΔΗΣ',,0);
// $queue[]=array('insertUser','dsiaravas','7801','ΔΗΜΗΤΡΗΣ ΣΙΑΡΑΒΑΣ',,0);
// $queue[]=array('insertUser','xkourlos','9903','ΧΡΗΣΤΟΣ ΚΟΥΡΛΟΣ',,0);
// $queue[]=array('insertUser','renatabob','5366','ΡΕΝΑΤΑ ΜΠΟΜΠΟΛΑΚΗ',,0);
// $queue[]=array('insertUser','dimpapadim','3055','ΔΗΜΗΤΡΗΣ ΠΑΠΑΔΗΜΗΤΡΙΟΥ',,0);
// $queue[]=array('insertUser','piakoilia','8906','ΠΙΑ ΚΟΙΛΙΑ',,0);
// $queue[]=array('insertUser','olkorol','7729','ΟΛΙΑ ΚΟΡΟΛ',,0);
// $queue[]=array('insertUser','emarini','7469','ΕΛΕΝΗ ΜΑΡΙΝΗ',,0);
// $queue[]=array('insertUser','kiriakidim','9510','ΚΥΡΙΑΚΗ ΔΗΜΗΤΡΑΚΟΠΟΥΛΟΥ',,0);
// $queue[]=array('insertUser','thkolias','7530','ΘΑΝΑΣΗΣ ΚΟΛΙΑΣ',,0);
// $queue[]=array('insertUser','apospatsi','8520','ΑΠΟΣΤΟΛΙΑ ΠΑΤΣΗ',,0);
// $queue[]=array('insertUser','mirtopap','7391','ΜΥΡΤΩ ΠΑΠΑΔΟΠΟΥΛΟΥ',,0);
// $queue[]=array('insertUser','iordpert','9067','ΙΟΡΔΑΝΗΣ ΠΕΡΤΕΤΣΟΓΛΟΥ',,0);
// $queue[]=array('insertUser','akontos','3017','ΑΓΓΕΛΟΣ ΚΟΝΤΟΣ',,0);
// $queue[]=array('insertUser','bbelefther','6180','ΜΠΑΜΠΗΣ ΕΛΕΥΘΕΡΙΟΥ',,0);
// $queue[]=array('insertUser','azarnaveli','1039','ΑΛΕΞΑΝΔΡΑ ΖΑΡΝΑΒΕΛΗ',,0);
// $queue[]=array('insertUser','pskoulas','7360','ΠΑΝΑΓΙΩΤΗΣ ΣΚΟΥΛΑΣ',,0);
// $queue[]=array('insertUser','vasiliskas','0563','ΒΑΣΙΛΗΣ ΚΑΣΙΔΙΑΡΗΣ',,0);
// $queue[]=array('insertUser','fleobilla','7101','ΦΩΤΕΙΝΗ ΛΕΟΜΠΙΛΛΑ',,0);
// $queue[]=array('insertUser','leonidkon','8610','ΛΕΩΝΙΔΑΣ ΚΩΝΣΤΑΝΤΙΝΟΥ',,0);
// $queue[]=array('insertUser','theonithal','9160','ΘΕΩΝΗ ΘΑΛΑΣΑΙΝΑ',,0);
// $queue[]=array('insertUser','anngiati','3055','ΑΝΝΑ ΓΚΙΑΤΗ',,0);
// $queue[]=array('insertUser','gnianouris','7660','ΓΙΩΡΓΟΣ ΝΙΑΝΟΥΡΗΣ',,0);

echo '<pre>';
foreach ($queue as $value) {
    echo "Command<br>";
    print_r($value);
    switch ($value[0]) {
        case 'deleteUnit':
            $unitToDelete=new Unit($value[1]);
            echo "Deletion ";
            $retVal = ($unitToDelete->deleteUnit()) ? "Succesful" : "Failed" ;
            echo "{$retVal}<br>";
            break;
        case 'insertUser':
            $username = $value[1];
            $password = password_hash($value[1].$value[2], PASSWORD_BCRYPT);
            $names = array();
            $names = explode(' ', $value[3]);
            $fname = $names[0];
            $lname = $names[1];
            $sql = "INSERT INTO {$config['schema']}.FE_USERS (USER_ID,USER_NAME,PASSWORD,USER_FIRST_NAME,USER_LAST_NAME) VALUES ({$config['schema']}.SEQ_FE_USERS_USER_ID.NEXTVAL,'{$username}','{$password}','{$fname}','{$lname}')";
            if ($db->query($sql)) {
                $sqlcur="SELECT {$config['schema']}.SEQ_FE_USERS_USER_ID.CURRVAL FROM DUAL";
                $rescur= $db->get_results($sqlcur,ARRAY_A);
                $curval=$rescur[0]['CURRVAL'];
                echo "<b>Succesfully</b> created user {$username} ({$value[2]}) and given USER_ID : {$curVal}<br>";
                $units = array();
                $units = explode('|', $value[4]);
                foreach ($units as $unit) {
                    $sql = "INSERT INTO {$config['schema']}.FE_USER_UNITS (USUN_ID,USUN_USER_ID,USUN_UNIT_ID) VALUES ({$config['schema']}.SEQ_FE_USER_UNITS_USUN_ID.NEXTVAL,{$curVal},{$unit})";
                    if ($db->query($sql)) {
                        echo "Success: {$user} / {$unit} <br>";
                    } else {
                        echo "=Failed: {$user} / {$unit} sql : {$sql} with error {$db->last_error} <br>";
                    }
                }
            } else {
                echo "<b>Failed</b> with message <strong>{db->last_error}</strong> while trying to apply SQL statement: <i>{$sql}</i>";
            }
            break;

        default:
            echo "Unknown Command: {$value[0]}";
            break;
    }
}