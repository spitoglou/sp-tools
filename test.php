<?php
/**
     *  @author Stavros Pitoglou <spitoglou@gmail.com>
     *  @version 0.1 Initial Tests
     */

// initialization
require_once "vendor/custom/php-console-master/src/PhpConsole/__autoload.php";

$handler = PhpConsole\Handler::getInstance();
$handler->getConnector()->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
$handler->getConnector()->getDebugDispatcher()->detectTraceAndSource = true;

$handler->start();

// plain debug
$handler->debug('test debug');

// debug with custom tag
$handler->debug('test debug', 'stavros');

// array debug
$handler->debug($_SERVER);
$arr = array(
    "test"  => 876,
    "test2" => "Stavros Πιτόγλου",
);
$handler->debug($arr);
$handler->debug(
    array(
        "item1" => 1,
        "item2" => 2,
    )
);

// object debug

/**
 * Test Class
 */
class TestObj
{
    /**
     * 1st Variable
     * @var mixed
     */
    public $var1;

    /**
     * 2nd Variable
     * @var mixed
     */
    public $var2;

    /**
     * constructor function
     * Set values to object vars
     * @param string
     */
    public function __construct($value = '')
    {
        $this->var1 = "Σταύρος";
        $this->var2 = "Πιτόγλου";
    }
}

$instance = new TestObj;
$handler->debug($instance);

//error handling

echo $stavros; // undefined var
file_get_contents('not_existed.file'); // non-existent file
                                        // uncommenting thw next line showcases exception handling (even if the script fails to complete execution)
                                        //throw new Exception('Some caught exception');

echo "End of script execution";
