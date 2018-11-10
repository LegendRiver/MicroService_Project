<?php

namespace DBService\Manager\OrionDB;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use DBService\Common\AbstractDBManager;
use DBService\DBField\OrionDB\DuplicateTaskDBFields;
use DBService\DBField\OrionDB\DuplicateTaskStatusDBFields;
use DBService\DBField\OrionDB\OrionTableNameConstants;
use DBService\Entity\OrionDB\DuplicateTaskInfoEntity;
use CommonMoudle\Constant\DBConstant;
use CommonMoudle\DBManager\DBParameter;

class DuplicateTaskDBManager extends AbstractDBManager
{
	private $duplicateTaskTableName;

	private $dbManager;

	public function __construct()
	{
		$this->duplicateTaskTableName = OrionTableNameConstants::DUPLICATE_TASK;
		$this->dbManager = OrionDBConnection::instance()->getOrionDBManager();
	}

	private function buildInsertSql()
	{
		$sql = 'INSERT INTO '. OrionTableNameConstants::DUPLICATE_TASK .' 
				(
				  '. DuplicateTaskDBFields::TASK_NAME .'
				, '. DuplicateTaskDBFields::USER_ID .'
				, '. DuplicateTaskDBFields::STATUS .'
				, '. DuplicateTaskDBFields::ROOT_PATH .'
				, '. DuplicateTaskDBFields::JSON_NAME .'
				, '. DuplicateTaskDBFields::CREATE_TIME .'
				, '. DuplicateTaskDBFields::COMPLETE_TIME .'
				) 
				VALUES(?, ?, ?, ?, ?, ?, ?)';
		return $sql;
	}

	public function insertDuplicateTask($dbField, $dbValue)
	{
		$insertSql = $this->buildInsertSql();
		foreach ($dbValue as $insertKey => $insertValue)
		{
			try
			{
				foreach ($dbField as $fieldKey => $fieldValue) {
					switch ($fieldValue) {
						case DuplicateTaskDBFields::TASK_NAME:
						case DuplicateTaskDBFields::ROOT_PATH:
						case DuplicateTaskDBFields::JSON_NAME:
							$insertParams[$insertKey][] = new DBParameter($fieldValue, $insertValue[$fieldKey], DBConstant::DB_TYPE_STRING);
							break;
						case DuplicateTaskDBFields::USER_ID:
						case DuplicateTaskDBFields::STATUS:
						case DuplicateTaskDBFields::CREATE_TIME:
						case DuplicateTaskDBFields::COMPLETE_TIME:
							$insertParams[$insertKey][] = new DBParameter($fieldValue, $insertValue[$fieldKey], DBConstant::DB_TYPE_INTEGER);
					}
				}
				$recordRows = $this->dbManager->executeSql($insertSql, $insertParams[$insertKey]);
			}
			catch (\Exception $e)
			{
				ServerLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
				return false;
			}
		}
		return $recordRows;
	}

	private function buildUpdateFieldSql($updateField)
	{
		$sql = '';
		foreach ($updateField as $updateKey => $updateValue)
		{
			if ($updateKey > 0)
			{
				$sql .= ', ' . $updateValue . '=?';
			}
			else if (0 == $updateKey)
			{
				$sql .= '  ' . $updateValue . '=?';
			}
		}
		return $sql;
	}

	private function buildWhere($whereField)
	{
		$sql = '';
		foreach ($whereField as $whereKey => $whereValue)
		{
			if ($whereKey > 0)
			{
				$sql .= ' AND ' . $whereValue . '=?';
			}
			else if (0 == $whereKey)
			{
				$sql .= ' WHERE '. $whereValue .'=?';
			}
		}
		return $sql;
	}

	private function buildUpdateSql($updateField, $whereField)
	{
		$sql = 'UPDATE '. OrionTableNameConstants::DUPLICATE_TASK .'
				SET ';

		$sqlConnection = $this->buildUpdateFieldSql($updateField);
		$sql .= $sqlConnection;

		$sqlConnectionWhere = $this->buildWhere($whereField);
		$sql .= $sqlConnectionWhere;
		return $sql;
	}

	private function bindParameter($updateFileld, $updateValues)
	{
		foreach ($updateValues as $updateKey => $updateValue)
		{
			switch ($updateFileld[$updateKey]) {
				case DuplicateTaskDBFields::TASK_NAME:
				case DuplicateTaskDBFields::ROOT_PATH:
				case DuplicateTaskDBFields::JSON_NAME:
					$updateParams[] = new DBParameter($updateFileld[$updateKey], $updateValue, DBConstant::DB_TYPE_STRING);
					break;
				case DuplicateTaskDBFields::USER_ID:
				case DuplicateTaskDBFields::STATUS:
				case DuplicateTaskDBFields::COMPLETE_TIME:
				case DuplicateTaskDBFields::CREATE_TIME:
				case DuplicateTaskDBFields::ID:
					$updateParams[] = new DBParameter($updateFileld[$updateKey], $updateValue, DBConstant::DB_TYPE_INTEGER);
			}
		}
		return $updateParams;
	}

	public function updateDuplicateTask($updateField, $updateValue, $whereField, $whereValue)
	{
		try
		{
			$updateSql = $this->buildUpdateSql($updateField, $whereField);

			$updateParams      = array();
			$mergeField = array_merge($updateField, $whereField);
			$mergeValue = array_merge($updateValue, $whereValue);
			$updateParams = $this->bindParameter($mergeField, $mergeValue);
			$recordRows = $this->dbManager->executeSql($updateSql, $updateParams);
		}
		catch (\Exception $e)
		{
			ServerLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
			return false;
		}
		return $recordRows;
	}

	private function buildJoinSqlByUserId()
	{
		$sql = 'SELECT 
				  '. duplicateTask .'.'. DuplicateTaskDBFields::ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::TASK_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::USER_ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::ROOT_PATH .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::JSON_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::CREATE_TIME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::COMPLETE_TIME .'
				, '. duplicateTaskStatus .'.'. DuplicateTaskStatusDBFields::DESCRIPTION .' 
				FROM '. OrionTableNameConstants::DUPLICATE_TASK .' '. duplicateTask .' 
				INNER JOIN '. OrionTableNameConstants::DUPLICATE_TASK_STATUS .' '. duplicateTaskStatus .' 
				ON 
				'. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .' = '. duplicateTaskStatus .'.'. DuplicateTaskStatusDBFields::ID .'
				WHERE 
				'. duplicateTask .'.'. DuplicateTaskDBFields::USER_ID .'=? ';
		return $sql;
	}

	public function selectTaskByUserId($userId, $fieldArray = array())
	{
		if(empty($fieldArray))
		{
			$fieldArray = DuplicateTaskDBFields::getInstance()->getValues();
		}
		$selectSql = $this->buildJoinSqlByUserId();

		if (!empty($userId))
		{
			$selectParams = array();
			$selectParams[] = new DBParameter(DuplicateTaskDBFields::USER_ID, $userId, DBConstant::DB_TYPE_INTEGER);
			$recordRows = $this->dbManager->querySql($selectSql, $selectParams);
		}
		if(false === $recordRows)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select table duplicate_task join duplicate_task_status by userId=' . $userId);
			return false;
		}
		$entityArray = array();
		foreach($recordRows as $row)
		{
			$entityArray[] = $this->buildDBEntity($row);
		}
		return $entityArray;

	}

	private function buildSqlByStatus()
	{
		$sql = 'SELECT 
				  '. duplicateTask .'.'. DuplicateTaskDBFields::ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::TASK_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::USER_ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::ROOT_PATH .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::JSON_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::CREATE_TIME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::COMPLETE_TIME .'
				FROM '. OrionTableNameConstants::DUPLICATE_TASK .' '. duplicateTask .' 
				WHERE 
				'. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .'=? ';
		return $sql;
	}

	public function selectTaskByStatus($status, $fieldArray = array())
	{
		if(empty($fieldArray))
		{
			$fieldArray = DuplicateTaskDBFields::getInstance()->getValues();
		}
		$selectSql = $this->buildSqlByStatus();

		if (!empty($status))
		{
			$selectParams = array();
			$selectParams[] = new DBParameter(DuplicateTaskDBFields::STATUS, $status, DBConstant::DB_TYPE_INTEGER);
			$recordRows = $this->dbManager->querySql($selectSql, $selectParams);
		}
		if(false === $recordRows)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select table duplicate_task by status=' . $status);
			return false;
		}
		$entityArray = array();
		foreach($recordRows as $row)
		{
			$entityArray[] = $this->buildDBEntity($row);
		}
		return $entityArray;
	}

	private function buildJoinSqlAll()
	{
		$sql = 'SELECT 
				  '. duplicateTask .'.'. DuplicateTaskDBFields::ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::TASK_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::USER_ID .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::ROOT_PATH .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::JSON_NAME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::CREATE_TIME .'
				, '. duplicateTask .'.'. DuplicateTaskDBFields::COMPLETE_TIME .' 
				, '. duplicateTaskStatus .'.'. DuplicateTaskStatusDBFields::DESCRIPTION .' 
				FROM 
				'. OrionTableNameConstants::DUPLICATE_TASK .' '. duplicateTask .' 
				INNER JOIN 
				'. OrionTableNameConstants::DUPLICATE_TASK_STATUS .' '. duplicateTaskStatus .' 
				ON 
				'. duplicateTask .'.'. DuplicateTaskDBFields::STATUS .' = '. duplicateTaskStatus .'.'. DuplicateTaskStatusDBFields::ID;

		return $sql;
	}

	public function selectTaskAll($fieldArray = array())
	{
		if(empty($fieldArray))
		{
			$fieldArray = DuplicateTaskDBFields::getInstance()->getValues();
		}
		$selectSql = $this->buildJoinSqlAll();

		$recordRows = $this->dbManager->querySql($selectSql);

		if(false === $recordRows)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select table duplicate_task join duplicate_task_status.');
			return false;
		}

		return $recordRows;
	}


	protected function initEntityCondition()
	{
		$this->dbEntityInstance = new DuplicateTaskInfoEntity();

		$this->field2FunctionName = array(
			DuplicateTaskDBFields::ID => 'setId',
			DuplicateTaskDBFields::TASK_NAME => 'setTaskName',
			DuplicateTaskDBFields::USER_ID => 'setUserId',
			DuplicateTaskDBFields::STATUS => 'setStatus',
			DuplicateTaskDBFields::ROOT_PATH => 'setRootPath',
			DuplicateTaskDBFields::JSON_NAME => 'setJsonName',
			DuplicateTaskDBFields::CREATE_TIME => 'setCreateTime',
			DuplicateTaskDBFields::COMPLETE_TIME => 'setCompleteTime',
			DuplicateTaskDBFields::DESCRIPTION => 'setDescription',
		);
	}

}