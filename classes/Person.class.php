<?php

/**
 * class Person
 */
class Person extends Base
{
	public $idNr;
	public $data;
	public $fname  = '';
	public $lname  = '';
	public $mother = '';
	public $father = '';
	public $dob    = '';
	public $gender;
	public $ketheaCode;
	public $code;
	public $physFiles = array();

	public function __construct($di, $id = '')
	{
		parent::__construct($di);
		if ($id)
		{
			$this->idNr = $id;
			$this->loadPersonAttrs($id);
			$this->saveMode = 'update';
		}
        $this->data['PERS_IS_MEMBER']=1;
        $this->data['USER_CREATE']=1;

	}

	/**
	 * @param $id
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

	public function calcKetheaCode()
	{
		# code...
	}
}
