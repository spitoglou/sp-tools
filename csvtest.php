<?php

require_once "lib/php-console-master/src/PhpConsole/__autoload.php";

$handler = PhpConsole\Handler::getInstance();
$handler->getConnector()->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
$handler->getConnector()->getDebugDispatcher()->detectTraceAndSource = true;

$handler->start();

$offset = 8;
$unit1  = 123;
$unit2  = 876;
$event  = array(
    "Oloklirosi" => "transfer",
    "Diakopi"    => "withdrawal",
);

$fp = fopen('mock.csv', 'r');
while ($csv_line = fgetcsv($fp, 1024, ','))
{
    //print_r($csv_line);
    $log[] = "Date Start {$csv_line[$offset]}";
    $log[] = "Date End {$csv_line[$offset + 1]}";
    $log[] = $event[$csv_line[$offset + 2]];
}
echo '<pre>';
print_r($log);
fclose($fp) || die("can not close file");
