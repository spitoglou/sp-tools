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

    public function __construct($di, $id = '')
    {
        $this->di = $di;
        if ($id)
        {
            $this->loadPersonAttrs($id);

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
}
