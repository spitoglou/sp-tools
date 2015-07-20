<?php
/**
     * unit class file
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */
namespace Sptools;

/**
 * History Class
 */
class Unit extends Base
{
    public $unit = array();
    public $structure;
    public $program;

    public function __construct($di, $id = '')
    {
        parent::__construct($di);
        if ($id) {
            $this->unit['UNIT_ID'] = $id;
            $this->loadAttrs($id);
            $this->saveMode = 'update';
        }
    }

    /**
     * @param $id
     */
    private function loadAttrs($id = '')
    {
        $sql        = "SELECT * from {$this->schema}.FE_UNITS where UNIT_ID={$id}";
        $result     = $this->executeQuery($sql);
        $this->unit = $result[0];

        $this->structure = new Structure($this->di, $this->unit['UNIT_STRU_ID']);
        $this->program   = new Program($this->di, $this->structure->stru['STRU_PROG_ID']);
    }

    /**
     * Delete current unit
     * @return number 1:success,0:failure
     */
    public function deleteUnit()
    {
        $ret=0;
        $sql = "DELETE FROM {$this->schema}.FE_USER_UNITS WHERE USUN_UNIT_ID={$this->unit['UNIT_ID']}";
        $this->executeStatement($sql);
        $sql = "DELETE FROM {$this->schema}.FE_UNITS where UNIT_ID={$this->unit['UNIT_ID']}";
        $retVal = ($this->executeStatement($sql)) ? 1 : 0 ;
        return $retVal;
    }
}
