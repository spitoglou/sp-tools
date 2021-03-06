<?php
/**
     * program class
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */
namespace Sptools;

/**
 * Program Class
 */
class Program extends Base
{
    public $prog = array();

    public function __construct($di, $id = '')
    {
        parent::__construct($di);
        if ($id) {
            $this->prog['PROG_ID'] = $id;
            $this->loadAttrs($id);
            $this->saveMode = 'update';
        }
    }

    /**
     * @param $id
     */
    private function loadAttrs($id = '')
    {
        $sql        = "SELECT * from {$this->schema}.FE_PROGRAMS where PROG_ID={$id}";
        $result     = $this->executeQuery($sql);
        $this->prog = $result[0];

    }
}
