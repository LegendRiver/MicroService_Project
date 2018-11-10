<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/10
 * Time: 上午10:47
 */

namespace DBService\Manager\OrionDB;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use DBService\Common\AbstractDBManager;
use DBService\DBField\OrionDB\OrionTableNameConstants;
use DBService\DBField\OrionDB\UserDBFields;
use DBService\Entity\OrionDB\UserInfoEntity;
use CommonMoudle\Constant\DBConstant;
use CommonMoudle\DBManager\DBParameter;

class UserDBManager extends AbstractDBManager
{
	private $userTableName;

	private $dbManager;

	public function __construct()
	{
		$this->userTableName = OrionTableNameConstants::USER_INFO;
		$this->dbManager = OrionDBConnection::instance()->getOrionDBManager();
	}
	public function selectUserByName($name, $fieldArray = array())
	{
		if(empty($fieldArray))
		{
			$fieldArray = UserDBFields::getInstance()->getValues();
		}
		$selectParamMap = array();
		$userNameParam = new DBParameter(UserDBFields::NAME, $name, DBConstant::DB_TYPE_STRING);
		$selectParamMap[UserDBFields::NAME] = $userNameParam;

		$recordRows = $this->dbManager->selectDbRecord($this->userTableName, $fieldArray, $selectParamMap);
		if(false === $recordRows)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select table user_info.' . $name);
			return false;
		}

		return $recordRows;
	}

	public function selectUserById($id, $fieldArray = array())
	{
		if(empty($fieldArray))
		{
			$fieldArray = UserDBFields::getInstance()->getValues();
		}
		$selectParamMap = array();
		$userIdParam = new DBParameter(UserDBFields::ID, $id, DBConstant::DB_TYPE_INTEGER);
		$selectParamMap[UserDBFields::ID] = $userIdParam;

		$recordRows = $this->dbManager->selectDbRecord($this->userTableName, $fieldArray, $selectParamMap);
		if(false === $recordRows)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select table user_info by id' . $id);
			return false;
		}

		return $recordRows;
	}

	protected function initEntityCondition()
	{
		$this->dbEntityInstance = new UserInfoEntity();

		$this->field2FunctionName = array(
			UserDBFields::ID => 'setId',
			UserDBFields::NAME => 'setName',
			UserDBFields::PHONE_NO => 'setPhone_no',
			UserDBFields::EMAIL => 'setEmail',
			UserDBFields::PASSWORD => 'setPassword'
		);
	}

}