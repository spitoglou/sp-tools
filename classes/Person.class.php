<?php

/**
 * class Person
 */
class Person
{
    public $fname     = '';
    public $lname     = '';
    public $mother    = '';
    public $father    = '';
    public $dob       = '';
    public $physFiles = array();
    private $di;
    private $saveMode = 'insert';

    public function __construct($di, $id = '')
    {
        $this->di = $di;
        if ($id)
        {
            $this->loadPersonAttrs($id);
            $this->saveMode = 'update';
        }
    }

    /**
     * @param $id
     */
    private function loadPersonAttrs($id = '')
    {
        $sql    = "SELECT * from persons where id={$id}";
        $result = $this->di->db->get_results($sql, ARRAY_A);
        $this->di->logger->debug($result, 'Result');
        $this->fname = $result[0]['fname'];
        $this->lname = $result[0]['lname'];
    }

    public function save()
    {
        switch ($this->saveMode)
        {
            case 'insert':
                # code...
                break;
            case 'update':
                # code...
                break;

            default:
                # code...
                break;
        }
    }
}
