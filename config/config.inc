<?php

// initialization
$_SESSION['DefaultDateFormat'] = 'd/m/Y';
mb_internal_encoding("UTF-8");
require_once "vendor/autoload.php";
require_once "vendor/custom/DateFunctions.inc";
require_once "vendor/custom/php-console-master/src/PhpConsole/__autoload.php";

$handler = PhpConsole\Handler::getInstance();
$handler->getConnector()->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
$handler->getConnector()->getDebugDispatcher()->detectTraceAndSource = true;

$handler->start();

spl_autoload_register(function ($class)
{
    // project-specific namespace prefix
    $prefix = '';
    // base directory for the namespace prefix
    $base_dir = 'classes/';
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    // get the relative class name
    $relative_class = substr($class, $len);
    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.class.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

//dependencies init
$app         = new Sptools\Di();
$app->logger = $handler;

//config
$config['dbtype']    = 'orcl';
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
