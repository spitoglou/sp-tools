<?php
/**
    * script that executes a queue of specific operations
    * 1. deleteUnit
    * example: $queue[]=array('deleteUnit',UNIT_ID);
    * 2. insertUser
    * example: $queue[]=array('insertUser','username','salt','FullName','unit1|unit2|...','1 : admin')
    * @author Stavros Pitoglou <spitoglou@gmail.com> 
    */

require_once 'config.inc';
$queue=array();

echo '<pre>';
foreach ($queue as $value) {
    echo "Command<br>";
    print_r($value);
    switch ($value[0]) {

        case 'deleteUnit':

        $unitToDelete=new Sptools\Unit($app,$value[1]);
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
            $curVal=$rescur[0]['CURRVAL'];

            echo "<b>Succesfully</b> created user {$username} ({$value[2]}) and given USER_ID : {$curVal}<br>";
            $units = array();
            $units = explode(',', $value[4]);
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