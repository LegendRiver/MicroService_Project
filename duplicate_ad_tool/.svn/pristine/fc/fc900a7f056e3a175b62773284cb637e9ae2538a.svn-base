<?php

namespace DuplicateAd\DBManager;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\ServiceHelper;
use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Constant\DAEndpointConstant;
use DuplicateAd\Constant\DAResponseField;
use DuplicateAd\Constant\RequestParamConstant;
use DuplicateAd\Constant\DACommonConstant;
use DuplicateAd\Entity\TaskByStatusEntity;

class DBServiceFacade
{
	public static function getDuplicateTaskByStatus($status)
	{
		if(empty($status))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
				'The param of status is empty, status: ' . $status . '.');
			return false;
		}
		$queryParam = array(RequestParamConstant::DB_DATA_SERVICE_STATUS => $status);

		$response = HttpClient::instance()->sendRequest(DACommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET,
			DAEndpointConstant::QUERY_DUPLICATE_TASK_FIELD, $queryParam);

		if(!ServiceHelper::checkResponse($response))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getDuplicateTaskByStatus#Failed to check response');
			return false;
		}

		$duplicateTaskBody = ServiceHelper::getBodyData($response);
		if (!array_key_exists(DAResponseField::VALUE_FIELD, $duplicateTaskBody))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getDuplicateTaskByStatus#Maybe valueField is null.');
			return false;
		}
		$taskValueField = CommonHelper::getArrayValueByKey(DAResponseField::VALUE_FIELD, $duplicateTaskBody);
		if (empty($taskValueField)){
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getDuplicateTaskByStatus#Maybe the valueField key is null.');
			return false;
		}
		$taskByStatusList = array();
		foreach ($taskValueField as $taskValue)
		{
			$taskByStatus = new TaskByStatusEntity();
			$taskByStatus->setId($taskValue[0]);
			$taskByStatus->setTaskName($taskValue[1]);
			$taskByStatus->setUserId($taskValue[5]);
			$taskByStatus->setRootPath($taskValue[3]);
			$taskByStatus->setJsonName($taskValue[4]);
			$taskByStatus->setCreateTime($taskValue[2]);
			$taskByStatusList[] = $taskByStatus;
		}
		return $taskByStatusList;
	}

	public static function updateDuplicateTaskById($status, $id)
	{
		$postParam = [
			RequestParamConstant::UPDATE_FIELD =>
				[
					RequestParamConstant::STATUS,
					RequestParamConstant::COMPLETE_TIME
				],
			RequestParamConstant::UPDATE_VALUE =>
				[
					$status,
					time()
				],
			RequestParamConstant::WHERE_FIELD =>
				[
					RequestParamConstant::ID
				],
			RequestParamConstant::WHERE_VALUE =>
				[
					$id
				]
		];

		$response = HttpClient::instance()->sendRequest(DACommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_POST,
			DAEndpointConstant::UPDATE_DUPLICATE_TASK_FIELD, array(), $postParam);

		if(!ServiceHelper::checkResponse($response))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#updateDuplicateTaskByID#Failed to check response');
			return false;
		}
		return true;
	}
}