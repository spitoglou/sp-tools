<?php
/**
 * event class
 * @author Stavros Pitoglou <s.pitoglou@csl.gr>
 */

/**
 * Event
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
	public $person;

	function __construct($di, $id = '', $type = '')
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
	 * @param $id
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

			default:
				# code...
				break;
		}
		$this->person = new Person($this->di, $this->pevn['PEVN_PERS_ID']);
	}

	/**
	 * [save description]
	 * @return [type] [description]
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
				$history=new History($this->di,$this->pevn['PEVN_PEHI_ID']);
				$comments = "TEST COMMENTS";
				if ($this->type == 'assignment'){
					$comments="To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} ανατέθηκε στην Υπηρεσία {$history->unit->program->prog['PROG_NAME']} / {$history->unit->structure->stru['STRU_NAME']} / {$history->unit->unit['UNIT_NAME']}";
				}
				if ($this->type == 'withdrawal'){
					$comments="To Μέλος {$history->person->data['PERS_LAST_NAME']} {$history->person->data['PERS_FIRST_NAME']} αποχώρησε από την Υπηρεσία {$history->unit->program->prog['PROG_NAME']} / {$history->unit->structure->stru['STRU_NAME']} / {$history->unit->unit['UNIT_NAME']}";
				}
				$sql .= "'{$comments}',";
				$sql .= "1,";
				$sql .= "SYSDATE";
				$sql .= ")";
				// $this->di->logger->debug($sql, "PEVN INSERT QUERY");
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
					// $this->di->logger->debug($sql, "ASSI INSERT QUERY");
					$this->executeStatement($sql);
				}
				if ($this->type == 'withdrawal')
	{
					$sql = "INSERT INTO {$this->schema}.FE_WITHDRAWALS (";
					$sql .= "WITD_ID,";
					$sql .= "WITD_PEVN_ID,";
					$sql .= "WITD_TYPE,";
					$sql .= "WITD_REAS_ID,";
					$sql .= "USER_CREATE,";
					$sql .= "DATE_CREATE,";
					$sql .= "DATE_UPDATE";
					$sql .= ") VALUES (";
					$sql .= "{$this->schema}.SEQ_FE_WITHDRAWALS_WITD_ID.NEXTVAL,";
					$sql .= "{$this->schema}.SEQ_FE_PERSON_EVENTS_PEVN_ID.CURRVAL,";
					$sql .= "1,";
					$sql .= "7,";
					$sql .= "1,";
					$sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']).",";
					$sql .= $this->formatDateForSQL($this->pevn['PEVN_DATE']);
					$sql .= ")";
					// $this->di->logger->debug($sql, "WITD INSERT QUERY");
					$this->executeStatement($sql);
				}
				break;

			default:
				# code...
				break;
		}
	}
}