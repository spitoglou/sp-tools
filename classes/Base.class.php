<?php

/**
 * BaseClass
 */
Abstract class Base
{
	protected $di;
	protected $saveMode = 'insert';
	protected $schema;

	function __construct($di)
	{
		$this->di     = $di;
		$this->schema = $this->di->conf['schema'];
	}

	/**
	 * [executeQuery description]
	 * @param  [type] $sql [description]
	 * @return [type]      [description]
	 */
	function executeQuery($sql)
	{
		try {
			$result = $this->di->db->get_results($sql, ARRAY_A);
			if (!$result)
			{
				throw new Exception($this->di->db->last_error, 1);
			}
		}
		catch (Exception $e)
		{
			$this->di->logger->debug($this->di->db->last_error, 'Query Error');
		}

		$this->di->logger->debug($result, 'Result');
		return $result;
	}

	/**
	 * [executeStatement description]
	 * @param  [type] $sql [description]
	 * @return [type]      [description]
	 */
	function executeStatement($sql)
	{
		try {
			$result = $this->di->db->query($sql, ARRAY_A);
			if (!$result)
			{
				throw new Exception($this->di->db->last_error, 1);
			}
		}
		catch (Exception $e)
		{
			$this->di->logger->debug($this->di->db->last_error, 'Query Error');
		}
	}

	/**
	 * [formatDateForSQL description]
	 * @param  [type] $DateEntry [description]
	 * @return [type]            [description]
	 */
	function formatDateForSQL($DateEntry)
	{
		$DateEntry = trim($DateEntry);

		if (Is_Date($DateEntry))
		{
			if (strpos($DateEntry, '/'))
			{
				$Date_Array = explode('/', $DateEntry);
			}
			elseif (strpos($DateEntry, '-'))
			{
				$Date_Array = explode('-', $DateEntry);
			}
			elseif (strlen($DateEntry) == 6)
			{
				$Date_Array[0] = substr($DateEntry, 0, 2);
				$Date_Array[1] = substr($DateEntry, 2, 2);
				$Date_Array[2] = substr($DateEntry, 4, 2);
			}
			elseif (strlen($DateEntry) == 8)
			{
				$Date_Array[0] = substr($DateEntry, 0, 4);
				$Date_Array[1] = substr($DateEntry, 4, 2);
				$Date_Array[2] = substr($DateEntry, 6, 2);
			}

			If ((int) $Date_Array[2] < 60)
			{
				$Date_Array[2] = '20'.$Date_Array[2];
			}
			elseif ((int) $Date_Array[2] > 59 AND (int) $Date_Array[2] < 100)
			{
				$Date_Array[0] = '19'.$Date_Array[2];
			}
			elseif ((int) $Date_Array[2] > 9999)
			{
				Return 0;
			}

			if ($_SESSION['DefaultDateFormat'] == 'd/m/Y')
			{
				Return 'To_Date(\''.$Date_Array[2].'-'.$Date_Array[1].'-'.$Date_Array[0].'\',\'YYYY-MM-DD\')';

			}
			elseif ($_SESSION['DefaultDateFormat'] == 'm/d/Y')
			{
				Return $Date_Array[2].'/'.$Date_Array[0].'/'.$Date_Array[1];

			}

		}
		else
		{
			Return "''";
		}

	} // end of function
}