<?php
/**
 * index.php.
 * @author Stavros Pitoglou <s.pitoglou@csl.gr>
 */

// initialization
//use League\Csv\Reader;
require_once "vendor/autoload.php";
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
$config['dbtype']    = 'mysql';
$config['my_dbuser'] = 'root';
$config['my_dbpass'] = '';
$config['dbhost']    = 'localhost';
$config['database']  = 'socservice';
$config['service']   = '';
$config['or_dbuser'] = '';
$config['or_dbpass'] = '';

$app->conf = $config;

//db conn
switch ($config['dbtype'])
{
    case 'mysql':
        //connection to mysql database
        $db = new ezSQL_mysql($config['my_dbuser'], $config['my_dbpass'], $config['database'], $config['dbhost']);
        // needed to display greek characters correctly
        $db->query("SET NAMES utf8");
        $db->query("SET CHARACTER SET utf8");
        break;
    case 'orcl':
        //connection to oracle database
        //putenv('NLS_LANG=AMERICAN_AMERICA.EL8ISO8859P7');
        putenv('NLS_LANG=GREEK_GREECE.AL32UTF8');
        $db = new ezSQL_oracle8_9($config['or_dbuser'], $config['or_dbpass'], $config['service']);
        break;
}

$app->db = $db;

$pers = new Person($app, 1);
echo '<pre>';
var_dump($pers);
//$a = new Reader();
