<?php

namespace Sptools;

/**
 * class DbStub
 * Provide a stub database class for test purposes.
 */
class DbStub
{
    private $di;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * magic function __call.
     *
     * @param string $name      [description]
     * @param string $arguments [description]
     *
     * @return mixed [description]
     */
    public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        //$this->di->logger->debug("Calling object method '$name' ".implode(', ', $arguments));
        switch ($name) {
            case 'query':
                return true;
                break;
            case 'get_results':
                return array(0 => array('CURRVAL' => 999, 'PERS_ID' => 888));
                break;

            default:
                # code...
                break;
        }
    }

    /**  As of PHP 5.3.0  */
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' ".implode(', ', $arguments)."\n";
    }
}
