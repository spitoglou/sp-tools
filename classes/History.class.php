<?php
/**
 * person history class
 * @author Stavros Pitoglou <s.pitoglou@csl.gr>
 */

/**
 * History Class
 */
class History extends Base
{
	public $pehi = array();
	public $person;
	public $unit;

	function __construct($di, $id = '')
	{
		parent::__construct($di);
		if ($id)
		{
			$this->pehi['PEHI_ID'] = $id;
			$this->loadAttrs($id);
			$this->saveMode = 'update';
		}
		$pehi['PEHI_TYPE']=1;
		$pehi['USER_CREATE']=1;

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
		$this->unit = new Unit($this->di, $this->pehi['PEHI_UNIT_ID']);

	}

	public function setUnit($value='')
	{
		$pehi['PEHI_UNIT_ID']=$value;
		$this->unit = new Unit($this->di, $value);
	}

	public function setPerson($value='')
	{
		$pehi['PEHI_PERS_ID']=$value;
		$this->person = new Person($this->di, $value);
	}
}