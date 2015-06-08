<?php
/**
 * program class
 * @author Stavros Pitoglou <s.pitoglou@csl.gr>
 */

/**
 * Program Class
 */
class Program extends Base
{
	public $prog = array();

	function __construct($di, $id = '')
	{
		parent::__construct($di);
		if ($id)
		{
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