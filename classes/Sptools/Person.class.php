<?php
/**
     * Person Class File
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */
namespace Sptools;

/**
 * Class Person
 * Implements person Objects
 */
class Person extends Base
{
    public $idNr;
    /**
     * person record array
     * @var array
     */
    public $data;
    /**
     * First Name
     * @var string
     */
    public $fname  = '';
    public $lname  = '';
    public $mother = '';
    public $father = '';
    public $dob    = '';
    public $gender;
    /**
     * Kethea 9-digit code
     * @var string
     */
    public $ketheaCode;
    public $code;
    public $physFiles = array();

    /**
     * constructor
     * @param DI $di Dependency Injector Object
     * @param int $id person id
     */
    public function __construct($di, $id = '')
    {
        parent::__construct($di);
        if ($id) {
            $this->idNr = $id;
            $this->loadPersonAttrs($id);
            $this->saveMode = 'update';
        }
        $this->data['PERS_IS_MEMBER'] = 1;
        $this->data['USER_CREATE']    = 1;

    }

    /**
     * @param int $id person id
     */
    private function loadPersonAttrs($id = '')
    {
        $sql          = "SELECT * from {$this->schema}.FE_PERSONS where PERS_ID={$id}";
        $result       = $this->executeQuery($sql);
        $this->data   = $result[0];
        $this->fname  = $result[0]['PERS_FIRST_NAME'];
        $this->lname  = $result[0]['PERS_LAST_NAME'];
        $this->mother = $result[0]['PERS_MOTHER_NAME'];
        $this->father = $result[0]['PERS_FATHER_NAME'];
        $this->dob    = $result[0]['PERS_BIRTH_DATE'];
        $this->gender = $result[0]['PERS_GENDER'];
        $this->code   = $result[0]['PERS_CODE'];

    }

    /**
     * Save operations for Person object
     * @return void
     */
    public function save($changefields = array())
    {
        switch ($this->saveMode)
        {
            case 'insert':
                $sql = "INSERT INTO {$this->schema}.FE_PERSONS (";
                $sql .= "PERS_ID,";
                $sql .= "PERS_CODE,";
                $sql .= "PERS_KETHEA_CODE,";
                $sql .= "PERS_LAST_NAME,";
                $sql .= "PERS_FIRST_NAME,";
                $sql .= "PERS_FATHER_NAME,";
                $sql .= "PERS_MOTHER_NAME,";
                $sql .= "PERS_BIRTH_DATE,";
                $sql .= "PERS_IS_MEMBER,";
                $sql .= "PERS_GENDER,";
                $sql .= "PERS_IS_ADDICT,";
                $sql .= "USER_CREATE,";
                $sql .= "DATE_CREATE";
                $sql .= ") VALUES (";
                $sql .= "{$this->schema}.SEQ_FE_PERSONS_PERS_ID.NEXTVAL,";
                $sql .= "'{$this->data['PERS_CODE']}',";
                $sql .= "'{$this->data['PERS_KETHEA_CODE']}',";
                $sql .= "'{$this->data['PERS_LAST_NAME']}',";
                $sql .= "'{$this->data['PERS_FIRST_NAME']}',";
                $sql .= "'{$this->data['PERS_FATHER_NAME']}',";
                $sql .= "'{$this->data['PERS_MOTHER_NAME']}',";
                $sql .= $this->formatDateForSQL($this->data['PERS_BIRTH_DATE']).",";
                $sql .= "1,"; //is_member
                $this->data['PERS_GENDER'] = substr(trim($this->data['PERS_KETHEA_CODE']), -1);
                $sql .= "'{$this->data['PERS_GENDER']}',";
                $sql .= "1,"; //is_addict
                $sql .= "1,";
                $sql .= "SYSDATE";
                $sql .= ")";
                //$this->di->logger->debug($sql,"PERS INSERT QUERY");
                $this->executeStatement($sql);

                $sql = "INSERT INTO {$this->schema}.FE_PHYSICAL_FILES (";
                $sql .= "PHYS_ID,";
                $sql .= "PHYS_CODE,";
                $sql .= "PHYS_PERS_ID,";
                $sql .= "PHYS_DISPLAY_CODE,";
                $sql .= "USER_CREATE,";
                $sql .= "DATE_CREATE";
                $sql .= ") VALUES (";
                $sql .= "{$this->schema}.SEQ_FE_PHYSICAL_FILES_PHYS_ID.NEXTVAL,";
                $code = explode('-', $this->data['PERS_CODE'])[1];
                if (!$code) {
                    $code=$this->data['PERS_CODE'];
                }
                $sql .= "'{$code}',";
                $sql .= "{$this->schema}.SEQ_FE_PERSONS_PERS_ID.CURRVAL,";
                $sql .= "'{$this->data['PERS_CODE']}',";
                $sql .= "1,";
                $sql .= "SYSDATE";
                $sql .= ")";
                $this->executeStatement($sql);
                break;

            case 'update':
                $sql = "UPDATE {$this->schema}.FE_PERSONS SET ";
                $change=array();
                foreach ($changefields as $value) {
                    if (strpos($value, 'DATE')!=false) {
                        $dateVal=$this->formatDateForSQL($this->data[$value]);
                        $change[] = "{$value} = {$dateVal}";

                    } else {
                        $change[] = "{$value} = '{$this->data[$value]}'";
                    }
                }
                $sql.= implode(',', $change);

                $sql.= " WHERE PERS_ID={$this->data['PERS_ID']}";
                $this->executeStatement($sql);
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * calculates kethea 9-digit code
     * @return string kethea code
     */
    public function calcKetheaCode()
    {
        # code...
    }

    /**
     * checks if there is a person record with the
     * given physical file. On success it returns 1
     * and loads the object from the database. On
     * failure it returns 0.
     * @param  string $value Physical file display code
     * @return int            1:success 2:failure
     */
    public function loadFromFile($value = '')
    {
        $result = 0;
        $sql    = "SELECT a.PERS_ID FROM {$this->schema}.FE_PERSONS a, {$this->schema}.FE_PHYSICAL_FILES b WHERE a.pers_id=b.phys_pers_id  and b.phys_display_code = '{$value}'";
        $res    = $this->executeQuery($sql);
        if ($res) {
            $result = 1;
            $this->loadPersonAttrs($res[0]['PERS_ID']);
            $this->saveMode = 'update';
        }
        return $result;

    }
}
