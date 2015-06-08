<?php
/**
 * structure class
 * @author Stavros Pitoglou <s.pitoglou@csl.gr>
 */

/**
 * Structure Class
 */
class Structure extends Base
{
	public $stru = array();
	public $program;

	function __construct($di, $id = '')
	{
		parent::__construct($di);
		if ($id)
		{
			$this->stru['STRU_ID'] = $id;
			$this->loadAttrs($id);
			$this->saveMode = 'update';
		}
	}

	/**
	 * @param $id
	 */
	private function loadAttrs($id = '')
	{
		$sql        = "SELECT * from {$this->schema}.FE_STRUCTURES where STRU_ID={$id}";
		$result     = $this->executeQuery($sql);
		$this->stru = $result[0];

		$this->program = new Program($this->di, $this->stru['STRU_PROG_ID']);
	}
}