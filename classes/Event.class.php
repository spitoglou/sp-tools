<?php
/**
     * event class
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */

/**
 * Event Class
 */
class Event extends Base
{
    public $pevn       = array();
    public $type       = '';
    public $assignment = array();
    public $transfer   = array();
    public $withdrawal = array();
    public $transport  = array();
    public $completion = array();
    public $graduation = array();
    public $release    = array();
    public $person;
    private $transferUnit;

    /**
     * constructor
     * @param Di $di   dependencies
     * @param string $id   id
     * @param string $type event type
     */
    public function __construct($di, $id = '', $type = '')
    {
        parent::__construct($di);
        $this->type = $type;
        if ($id)
        {
            $this->pevn['PEVN_ID'] = $id;
            $this->loadAttrs($id);
            $this->saveMode = 'update';
        }

    }

    /**
     * Fill object from database
     * @param integer $id event id
     */
    private function loadAttrs($id = '')
    {
        $sql        = "SELECT * from {$this->schema}.FE_PERSON_EVENTS where PEVN_ID={$id}";
        $result     = $this->executeQuery($sql);
        $this->pevn = $result[0];
        switch ($this->type)
        {
            case 'assignment':
                $sql              = "SELECT * from {$this->schema}.FE_ASSIGNMENTS where ASSI_PEVN_ID={$id}";
                $result           = $this->executeQuery($sql);
                $this->assignment = $result[0];
                break;
            case 'withdrawal':
            case 'witdoutin':
            case 'witdoutout':
                $sql              = "SELECT * from {$this->schema}.FE_WITHDRAWALS where WITD_PEVN_ID={$id}";
                $result           = $this->executeQuery($sql);
                $this->withdrawal = $result[0];
                break;
            case 'transfer':
                $sql            = "SELECT * from {$this->schema}.FE_TRANSFERS where TRAN_PEVN_ID={$id}";
                $result         = $this->executeQuery($sql);
                $this->transfer = $result[0];
                break;
            case 'completion':
                $sql              = "SELECT * from {$this->schema}.FE_COMPLETIONS where COML_PEVN_ID={$id}";
                $result           = $this->executeQuery($sql);
                $this->completion = $result[0];
                break;
            case 'graduation':
                $sql              = "SELECT * from {$this->schema}.FE_GRADUATIONS where GRAD_PEVN_ID={$id}";
                $result           = $this->executeQuery($sql);
                $this->graduation = $result[0];
                break;
            case 'transport':
                $sql             = "SELECT * from {$this->schema}.FE_TRANSPORTS where TRAP_PEVN_ID={$id}";
                $result          = $this->executeQuery($sql);
                $this->transport = $result[0];
                break;
            case 'release':
                $sql           = "SELECT * from {$this->schema}.FE_RELEASES where RELE_PEVN_ID={$id}";
                $result        = $this->executeQuery($sql);
                $this->release = $result[0];
                break;

            default:
                # code...
                break;
        }
        $this->person = new Person($this->di, $this->pevn['PEVN_PERS_ID']);
    }

    /**
     * Save Operations
     * @return void
     */
    public function save()
    {
        switch ($this->saveMode)
        {
            case 'insert':
                $sql = "INSERT INTO {$this->schema}.FE_PERSON_EVENTS (";
                $sql .= "PEVN_ID,";
                $sql .= "PEVN_PERS_ID,";
                $sql .= "PEVN_PEHI_ID,";
                $sql .= "PEVN_DATE,";
                $sql .= "PEVN_SUBC_ID,";
                $sql .= "PEVN_COMMENTS,";
                $sql .= "USER_CREATE,";
                $sql .= "DATE_CREATE";
                $sql .= ") VALUES (";
                $sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.NEXTVAL,";
                $sql .= "{$this->pevn['PEVN_PERS_ID']},";
                $sql .= "{$this->pevn['PEVN_PEHI_ID']},";
                $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                $sql .= "1,";
                $sql .= "'{$this->makeComments()}',";
                $sql .= "1,";
                $sql .= "SYSDATE";
                $sql .= ")";
                $this->executeStatement($sql);
                if ($this->type == 'assignment')
                {
                    $sql = "INSERT INTO {$this->schema}.FE_ASSIGNMENTS (";
                    $sql .= "ASSI_ID,";
                    $sql .= "ASSI_PEVN_ID,";
                    $sql .= "USER_CREATE,";
                    $sql .= "DATE_CREATE,";
                    $sql .= "DATE_UPDATE,";
                    $sql .= "ASSI_CRISIS";
                    $sql .= ") VALUES (";
                    $sql .= "{$this->schema}.SEQ_FE_ASSIGNMENTS_ASSI_ID.NEXTVAL,";
                    $sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.CURRVAL,";
                    $sql .= "1,";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                    $sql .= "0";
                    $sql .= ")";
                    $this->executeStatement($sql);
                }
                if (in_array($this->type, array('withdrawal', 'witdoutin', 'witdoutout')))
                {
                    $sql = "INSERT INTO {$this->schema}.FE_WITHDRAWALS (";
                    $sql .= "WITD_ID,";
                    $sql .= "WITD_PEVN_ID,";
                    $sql .= "WITD_TYPE,";
                    $sql .= "WITD_OUT_KETHEA,";
                    $sql .= "WITD_REAS_ID,";
                    $sql .= "USER_CREATE,";
                    $sql .= "DATE_CREATE,";
                    $sql .= "DATE_UPDATE";
                    $sql .= ") VALUES (";
                    $sql .= "{$this->schema}.SEQ_FE_WITHDRAWALS_WITD_ID.NEXTVAL,";
                    $sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.CURRVAL,";
                    switch ($this->type)
        {
                        case 'withdrawal':
                                $sql .= "1,NULL,";
                                break;
                        case 'witdoutin':
                                $sql .= "2,0,";
                                break;
                        case 'witdoutout':
                                $sql .= "2,1,";
                                break;
                    }
                    $sql .= "7,";
                    $sql .= "1,";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']);
                    $sql .= ")";
                    $this->executeStatement($sql);
                }
                if ($this->type == 'transfer')
                {
                    $sql = "INSERT INTO {$this->schema}.FE_TRANSFERS (";
                    $sql .= "TRAN_ID,";
                    $sql .= "TRAN_PEVN_ID,";
                    $sql .= "TRAN_UNIT_ID,";
                    $sql .= "TRAN_PENDING,";
                    $sql .= "TRAN_CANCELED,";
                    $sql .= "TRAN_APPROVE_DATE,";
                    $sql .= "USER_CREATE,";
                    $sql .= "DATE_CREATE,";
                    $sql .= "DATE_UPDATE";
                    $sql .= ") VALUES (";
                    $sql .= "{$this->schema}.SEQ_FE_TRANSFERS_TRAN_ID.NEXTVAL,";
                    $sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.CURRVAL,";
                    $sql .= $this->transferUnit->unit['UNIT_ID'].","; //unit_id
                    $sql .= "0,";
                    $sql .= "0,";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                    $sql .= "1,";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
                    $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']);
                    $sql .= ")";
                    $this->executeStatement($sql);
                }
                if ($this->type == 'completion')
    {
                    $this->insPlainEvent('FE_COMPLETIONS', 'SEQ_FE_COMPLETIONS_COML_ID', 'COML');
                }
                if ($this->type == 'graduation')
    {
                    $this->insPlainEvent('FE_GRADUATIONS', 'SEQ_FE_GRADUATIONS_GRAD_ID', 'GRAD');
                }
                if ($this->type == 'transport')
    {
                    $this->insPlainEvent('FE_TRANSPORTS', 'SEQ_FE_TRANSPORTS_TRAP_ID', 'TRAP');

                }
                if ($this->type == 'release')
    {
                    $this->insPlainEvent('FE_RELEASES', 'SEQ_FE_RELEASES_RELE_ID', 'RELE');
                }
                break;

            default:
                // code...
                break;
        }
    }

    /**
     * set destination unit for transfers
     * @param int $id unit_id
     */
    public function setTransferUnit($id)
    {
        $this->transferUnit = new Unit($this->di, $id);
    }

    /**
     * create comment line
     * @return string comment line
     */
    private function makeComments()
    {
        $history = new History($this->di, $this->pevn['PEVN_PEHI_ID']);
        if ($this->type == 'assignment')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} ανατέθηκε στην Υπηρεσία {$history->unit->program->prog['PROG_NAME']} / {$history->unit->structure->stru['STRU_NAME']} / {$history->unit->unit['UNIT_NAME']}";
        }
        if ($this->type == 'withdrawal')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} αποχώρησε από την Υπηρεσία {$history->unit->program->prog['PROG_NAME']} / {$history->unit->structure->stru['STRU_NAME']} / {$history->unit->unit['UNIT_NAME']}";
        }
        if ($this->type == 'witdoutin')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} παραπέμφθηκε Εκτός Πλαισίου (Εντός ΚΕΘΕΑ)";
        }
        if ($this->type == 'witdoutout')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} παραπέμφθηκε Εκτός Πλαισίου (Εκτός ΚΕΘΕΑ)";
        }
        if ($this->type == 'transfer')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} παραπέμφθηκε στην Υπηρεσία {$this->transferUnit->program->prog['PROG_NAME']} / {$this->transferUnit->structure->stru['STRU_NAME']} / {$this->transferUnit->unit['UNIT_NAME']}";
        }
        if ($this->type == 'completion')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} ολοκλήρωσε στην Υπηρεσία {$history->unit->program->prog['PROG_NAME']} / {$history->unit->structure->stru['STRU_NAME']} / {$history->unit->unit['UNIT_NAME']}";
        }
        if ($this->type == 'graduation')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} αποφοίτησε με επιτυχία από το πρόγραμμα {$history->unit->program->prog['PROG_NAME']}";
        }
        if ($this->type == 'transport')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} αποχώρησε από το πρόγραμμα {$history->unit->program->prog['PROG_NAME']} λόγω Μεταγωγής";
        }
        if ($this->type == 'release')
        {
            return "To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} αποχώρησε από το πρόγραμμα {$history->unit->program->prog['PROG_NAME']} λόγω Αποφυλάκισης";
        }
        return "PLACEHOLDER COMMENTS";
    }

    /**
     * @param $table
     * @param $seq
     * @param $prefix
     */
    private function insPlainEvent($table, $seq, $prefix)
    {
        $sql = "INSERT INTO {$this->schema}.{$table} (";
        $sql .= "{$prefix}_ID,";
        $sql .= "{$prefix}_PEVN_ID,";
        $sql .= "USER_CREATE,";
        $sql .= "DATE_CREATE,";
        $sql .= "DATE_UPDATE";
        $sql .= ") VALUES (";
        $sql .= "{$this->schema}.{$seq}.NEXTVAL,";
        $sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.CURRVAL,";
        $sql .= "1,";
        $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
        $sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']);
        $sql .= ")";
        $this->executeStatement($sql);
    }
}
