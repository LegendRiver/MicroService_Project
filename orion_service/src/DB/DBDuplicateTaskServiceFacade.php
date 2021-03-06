<?php
namespace OrionService\DB;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\ServiceHelper;
use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Service\ServiceBaseConstant;
use OrionService\Constant\CommonConstant;
use OrionService\Constant\SendRequestParamConstant;
use OrionService\Constant\ServiceEndpointConstant;

class DBDuplicateTaskServiceFacade
{
	public static function duplicateTaskInsert($taskName, $userId, $rootPath, $jsonName)
	{
		$postParam = [
			SendRequestParamConstant::DB_FIELD =>
			[
				SendRequestParamConstant::TASK_NAME,
				SendRequestParamConstant::USER_ID,
				SendRequestParamConstant::STATUS,
				SendRequestParamConstant::ROOT_PATH,
				SendRequestParamConstant::JSON_NAME,
				SendRequestParamConstant::CREATE_TIME,
				SendRequestParamConstant::COMPLETE_TIME
			],
			SendRequestParamConstant::DB_VALUE =>
			[
				$taskName,
				$userId,
				1,
				$rootPath,
				$jsonName,
				time(),
				0
			]
		];
		$dbResponse = HttpClient::instance()->sendRequest(CommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_POST, ServiceEndpointConstant::INSERT_DB_DUPLICATE_TASK, array(), $postParam);
		if(!ServiceHelper::checkResponse($dbResponse))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#insertDuplicateTask#Failed to check response');
			return false;
		}
		return true;
	}

	public static function selectTaskByUserId($userId)
	{
		$queryParam = array(
			SendRequestParamConstant::DB_SERVICE_USER_ID => $userId
		);

		$dbResponse = HttpClient::instance()->sendRequest(CommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET, ServiceEndpointConstant::SELECT_TASK_BY_USER_ID, $queryParam);
		if(!ServiceHelper::checkResponse($dbResponse))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryDuplicateTask#Failed to check response');
			return false;
		}
		$body = $dbResponse->getBody();
		if (empty($body))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryDuplicateTask#Failed to get body');
			return false;
		}
		$taskData = json_decode($body, true);
		if (empty($taskData))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryDuplicateTask#Failed to get taskData');
			return false;
		}
		return CommonHelper::getArrayValueByKey(ServiceBaseConstant::BODY_DATA, $taskData);
	}
}