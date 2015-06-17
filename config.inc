<?php

// initialization
$_SESSION['DefaultDateFormat'] = 'd/m/Y';
require_once "vendor/autoload.php";
require_once "lib/DateFunctions.inc";
require_once "lib/php-console-master/src/PhpConsole/__autoload.php";

$handler = PhpConsole\Handler::getInstance();
$handler->getConnector()->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
$handler->getConnector()->getDebugDispatcher()->detectTraceAndSource = true;

$handler->start();

// custom class dir
define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_register(function ($class)
{
    require str_replace("\\", PATH_SEPARATOR, $class).'.class.php';
});

//dependencies init
$app         = new Di();
$app->logger = $handler;

//config
$config['dbtype']    = 'stub';
$config['my_dbuser'] = 'root';
$config['my_dbpass'] = '';
$config['dbhost']    = 'localhost';
$config['database']  = 'socservice';
$config['service']   = '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.84)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=orcl)))';
$config['schema']    = 'FE_PROD';
$config['schema']    = 'FE_TEST';
$config['or_dbuser'] = 'CS';
$config['or_dbpass'] = 'CS';

$app->conf = $config;

require_once 'dbconn.inc';
