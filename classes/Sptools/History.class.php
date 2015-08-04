<?php
/**
     * person history class
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */
namespace Sptools;

/**
 * History Class
 */
class History extends Base
{
    public $pehi = [];
    public $person;
    public $unit;

    function __construct($di, $id = '')
    {
        parent::__construct($di);
        if ($id) {
            $this->pehi['PEHI_ID'] = $id;
            $this->loadAttrs($id);
            $this->saveMode = 'update';
        }
        $this->pehi['PEHI_TYPE']   = 1;
        $this->pehi['USER_CREATE'] = 1;

    }

    /**
     * @param $id
     */
    private function loadAttrs($id = '')
    {
        $sql        = "SELECT * from {$this->schema}.FE_PERSONS_HISTORY where PEHI_ID={$id}";
        $result     = $this->executeQuery($sql);
        $this->pehi = $result[0];

        $this->person = new Person($this->di, $this->pehi['PEHI_PERS_ID']);
        $this->unit   = new Unit($this->di, $this->pehi['PEHI_UNIT_ID']);

    }

    /**
     * @param $value
     */
    public function setUnit($value = '')
    {
        $this->pehi['PEHI_UNIT_ID'] = $value;
        $this->unit           = new Unit($this->di, $value);
    }

    /**
     * @param $value
     */
    public function setPerson($value = '')
    {
        $this->pehi['PEHI_PERS_ID'] = $value;
        $this->person         = new Person($this->di, $value);
    }

    /**
     * Save Function
     */
    public function save()
    {
        switch ($this->saveMode)
        {
            case 'update':
                # code...
                break;

            case 'insert':
                $sql = "INSERT INTO {$this->schema}.FE_PERSONS_HISTORY (";
                $sql.="PEHI_ID,";
                $sql.="PEHI_UNIT_ID,";
                $sql.="PEHI_PERS_ID,";
                $sql.="PEHI_START_DATE,";
                $sql.="PEHI_END_DATE,";
                $sql.="PEHI_TYPE,";
                $sql.="USER_CREATE,";
                $sql.="DATE_CREATE";
                $sql.=") VALUES (";
                $sql.="{$this->schema}.SEQ_FE_PERSONS_HISTORY_PEHI_ID.NEXTVAL,";
                $sql.="{$this->pehi['PEHI_UNIT_ID']},";
                $sql.="{$this->pehi['PEHI_PERS_ID']},";
                $sql.= $this->formatDateForSQL($this->pehi['PEHI_START_DATE']).",";
                $sql.= $this->formatDateForSQL($this->pehi['PEHI_END_DATE']).",";
                $sql.="{$this->pehi['PEHI_TYPE']},";
                $sql.="1,";
                $sql.="SYSDATE";
                $sql.=")";
                $this->executeStatement($sql);
                break;

            default:
                # code...
                break;
        }
    }
}
