<?php
use Sptools\Person;
use Sptools\History;
use Sptools\Event;

/**
 * main migration process function
 * @param  string $file    file to be parsed
 * @param  int  $fakelos offset for phys file data location
 * @param  int $offset  offset for the first column to be parsed
 * @param  int $unit1   UNIT_ID
 * @param  int $unit2   UNIT_ID to transer to
 * @param  array $event   array of event conversion table
 * @return void          
 */
function processFile($file, $fakelos, $offset, $unit1, $unit2, $event)
{
    global $app, $config;
    $errors=array();
    echo '<pre>';
    ($fp = fopen("kethea_migr/{$file}.csv", 'r')) || die('problem');
    $errors['header'] = "{$file}|offset:{$offset}";
    $i = 0;
    while ($csv_line = fgetcsv($fp, 1024, ';'))
    {
        set_time_limit(60);
        $line      = implode('|', $csv_line);
        $pers      = new Person($app);
        $dateStart = $csv_line[$offset + 1];
        $dateEnd   = $csv_line[$offset + 2];

        if ($pers->loadFromFile($csv_line[$fakelos]))
        {
            if ($dateStart)
            {
                //check match fot event types
                if (!$event[$csv_line[$offset + 3]])
                {
                    $errors[$i] .= '-could not match event|';
                }
                else
                {
                    //everything OK datawise

                    //create person history
                    $newph = new History($app);
                    $newph->setUnit($unit1);
                    $newph->setPerson($pers->data['PERS_ID']);
                    $newph->pehi['PEHI_START_DATE'] = $dateStart;
                    $newph->pehi['PEHI_END_DATE']   = $dateEnd;
                    $newph->save();

                    $pehi_id = $newph->executeQuery("SELECT {$config['schema']}.SEQ_FE_PERSONS_HISTORY_PEHI_ID.CURRVAL FROM DUAL")[0]['CURRVAL'];

                    //create events

                    //if previous column is empty
                    if (!$csv_line[$offset - 1])
                    {
                        //create assignment
                        $newpevn = new Event($app, '', 'assignment');

                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateStart;
                        $newpevn->save();

                    }

                    switch ($event[$csv_line[$offset + 3]])
                    {
                        case 'withdrawal':
                        case 'witdoutin':
                        case 'witdoutout':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', $event[$csv_line[$offset + 3]]);
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->save();
                        break;
                        case 'transfer':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'transfer');
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->setTransferUnit($unit2);
                        $newpevn->save();
                        break;
                        case 'completion':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'completion');
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->save();
                        break;
                        case 'graduation':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'graduation');
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->save();
                        break;
                        case 'transport':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'transport');
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->save();
                        break;
                        case 'release':
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'release');
                        $newpevn->pevn['PEVN_PERS_ID'] = $pers->data['PERS_ID'];
                        $newpevn->pevn['PEVN_PEHI_ID'] = $pehi_id;
                        $newpevn->pevn['PEVN_DATE']    = $dateEnd;
                        $newpevn->save();
                        break;

                        default:
                            # code...
                        break;
                    }
                }
            }
        }
        else
        {
            $errors[$i] .= "-failed to find person from file ({$csv_line[$fakelos]})|";
        } //pers loadfromfile
        $log[] = $csv_line[$fakelos];
        $log[] = "Date Start {$csv_line[$offset + 1]}";
        $log[] = "Date End {$csv_line[$offset + 2]}";
        $log[] = $event[$csv_line[$offset + 3]];
        if ($errors[$i])
        {
            $errors[$i] .= "{$line}|";
        }
        $i++;
        unset($newph);
        unset($newpevn);
    } //end while
    $app->logger->debug($log, 'Run Log');
    $app->logger->debug($errors, 'Error Log');
    print_r($errors);
    fclose($fp) || die("can not close file");
}

/**
 * person insertion 
 * @param  string  $file     file to be parsed
 * @param  string  $fakelos  [description]
 * @param  boolean $extended [description]
 * @return void
 */
function curatePersons($file, $fakelos, $extended = false)
{
    global $app, $config;
    ($fp = fopen("kethea_migr/{$file}.csv", 'r')) || die('problem');
    while ($csv_line = fgetcsv($fp, 1024, ';'))
    {
        set_time_limit(60);
        $line = implode('|', $csv_line);

        $pers = new Person($app);
        if (!$pers->loadFromFile($csv_line[$fakelos]))
        {
            $pers->data['PERS_LAST_NAME']   = $csv_line[3];
            $pers->data['PERS_FIRST_NAME']  = $csv_line[4];
            $pers->data['PERS_KETHEA_CODE'] = $csv_line[5];
            $pers->data['PERS_BIRTH_DATE']  = '1/1/1900';
            $pers->data['PERS_FATHER_NAME'] = '----------';
            $pers->data['PERS_MOTHER_NAME'] = '----------';
            if ($extended)
            {
                if ($csv_line[6])
                {
                    $pers->data['PERS_BIRTH_DATE'] = $csv_line[6];
                }
                if ($csv_line[7])
                {
                    $pers->data['PERS_FATHER_NAME'] = $csv_line[7];
                }
                if ($csv_line[8])
                {
                    $pers->data['PERS_MOTHER_NAME'] = $csv_line[8];
                }

            }
            $pers->data['PERS_CODE'] = $csv_line[$fakelos];
            $pers->save();
            unset($pers);
        }
    } //while (csv parse loop)
    fclose($fp) || die("can not close file");
}


function createEvent($file,$fakelos,$offset,$eventType) {
    global $app, $config;
    ($fp = fopen("kethea_migr/{$file}.csv", 'r')) || die('problem');
    while ($csv_line = fgetcsv($fp, 1024, ';'))
    {
    }
    fclose($fp) || die("can not close file");
}

function updatePersonExtended($file,$fakelos,$force=false) {
    global $app, $config;
    ($fp = fopen("kethea_migr/{$file}.csv", 'r')) || die('problem');
    while ($csv_line = fgetcsv($fp, 1024, ';'))
    {
        set_time_limit(60);
        $line = implode('|', $csv_line);

        $pers = new Person($app);
        if ($pers->loadFromFile($csv_line[$fakelos]))
        {
            $changeDetected=false;
            $changeLog='';
            $changeFields=array();
            $fields=array(6=>'PERS_BIRTH_DATE',7=>'PERS_FATHER_NAME',8=>'PERS_MOTHER_NAME');
            foreach ($fields as $key=>$value) {
                $dbValue=$pers->data[$value];
                $csvValue=$csv_line[$key];
                if ( $dbValue!= $csvValue) {
                    $changeDetected=true;
                    $changeLog.="{$value}: Database = {$dbValue} / File = {$csvValue}<br>";
                    // if ($dbValue && $dbValue!='1/1/1900' && !strpos($dbValue,'--') && !$force) {
                    if (!$csvValue) {
                        $changeLog.='Change cancelled<br>';
                    } else {
                        $changeLog.='Change on the way<br>';
                        $pers->data[$value]=$csvValue;
                        $changeFields[]=$value;
                    }

                }
            }
            echo $changeLog;
            $pers->save($changeFields);    
            

        } else {
            echo "Person not found in line {$line}<br>";
        }
    } //while (csv parse loop)
    fclose($fp) || die("can not close file");
}