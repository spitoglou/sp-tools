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
}