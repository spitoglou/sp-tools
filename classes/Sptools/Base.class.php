<?php

namespace Sptools;

/**
 * BaseClass.
 */
abstract class Base
{
    protected $di;
    protected $saveMode = 'insert';
    protected $schema;

    /**
     * Base Class constructor.
     *
     * @param DI $di DI object
     */
    public function __construct($di)
    {
        $this->di = $di;
        $this->schema = $this->di->conf['schema'];
    }

    /**
     * Execute query and expect resultset (select).
     *
     * @param string $sql SQL query clause
     *
     * @return array Query Result in an associative array
     */
    public function executeQuery($sql)
    {
        try {
            $result = $this->di->db->get_results($sql, ARRAY_A);
            if (!$result) {
                throw new \Exception($this->di->db->last_error, 1);
            }
        } catch (\Exception $e) {
            $this->di->logger->debug($sql.' / '.$this->di->db->last_error, 'Query Error');
        }

        return $result;
    }

    /**
     * Execute DB statement (insert, update etc.).
     *
     * @param string $sql SQL Statement
     */
    public function executeStatement($sql)
    {
        try {
            $result = $this->di->db->query($sql);
            if (!$result) {
                throw new \Exception($this->di->db->last_error, 1);
            }
        } catch (\Exception $e) {
            $this->di->logger->debug($sql.' / '.$this->di->db->last_error, 'Statement Error');
        }
    }

    /**
     * [formatDateForSQL description].
     *
     * @param string $DateEntry given date
     *
     * @return string formatted sql date argument
     */
    public function formatDateForSQL($DateEntry)
    {
        $DateEntry = trim($DateEntry);
        $Date_Array = array();

        if (Is_Date($DateEntry)) {
            if (strpos($DateEntry, '/')) {
                $Date_Array = explode('/', $DateEntry);
            } elseif (strpos($DateEntry, '-')) {
                $Date_Array = explode('-', $DateEntry);
            } elseif (strlen($DateEntry) == 6) {
                $Date_Array[0] = substr($DateEntry, 0, 2);
                $Date_Array[1] = substr($DateEntry, 2, 2);
                $Date_Array[2] = substr($DateEntry, 4, 2);
            } elseif (strlen($DateEntry) == 8) {
                $Date_Array[0] = substr($DateEntry, 0, 4);
                $Date_Array[1] = substr($DateEntry, 4, 2);
                $Date_Array[2] = substr($DateEntry, 6, 2);
            }

            if ((int) $Date_Array[2] < 60) {
                $Date_Array[2] = '20'.$Date_Array[2];
            } elseif ((int) $Date_Array[2] > 59 && (int) $Date_Array[2] < 100) {
                $Date_Array[0] = '19'.$Date_Array[2];
            } elseif ((int) $Date_Array[2] > 9999) {
                return 0;
            }

            if ($_SESSION['DefaultDateFormat'] == 'd/m/Y') {
                return 'To_Date(\''.$Date_Array[2].'-'.$Date_Array[1].'-'.$Date_Array[0].'\',\'YYYY-MM-DD\')';
            } elseif ($_SESSION['DefaultDateFormat'] == 'm/d/Y') {
                return $Date_Array[2].'/'.$Date_Array[0].'/'.$Date_Array[1];
            }
        } else {
            return "''";
        }
    } // end of function
}
