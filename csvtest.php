<?php

$final   = true;
$file    = 'kittaro';
$fakelos = 2;
// TODO : make an array of arguments for multiple passes in one run
$offset = 7;
$unit1  = 1;
$unit2  = 876;
$event  = array
(
    "ΟΛΟΚΛΗΡΩΣΗ" => "transfer",
    "ΔΙΑΚΟΠΗ"       => "withdrawal",
    ""                     => "open",
);

echo '<pre>';
($fp = fopen("kethea_migr/{$file}.csv", 'r')) || die('problem');
$i = 0;
while ($csv_line = fgetcsv($fp, 1024, ';'))
{
    //print_r($csv_line);
    set_time_limit(60);
    $line      = implode('|', $csv_line);
    $pers      = new Person($app);
    $dateStart = $csv_line[$offset + 1];
    $dateEnd   = $csv_line[$offset + 2];
    if ($pers->loadFromFile($csv_line[$fakelos]))
    {
        // TODO : Remove $dateEnd from condition (open histories)
        if (!$dateStart || !$dateEnd)
        {
            $errors[$i] .= '-non-existent dates|';
        }
        else
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
                        unset($newpevn);
                        $newpevn                       = new Event($app, '', 'withdrawal');
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
