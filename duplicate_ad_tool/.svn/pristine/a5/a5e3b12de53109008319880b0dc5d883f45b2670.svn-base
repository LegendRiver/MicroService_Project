<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\MailerHelper;
use CommonMoudle\Helper\ServiceHelper;
use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Business\DuplicateHandler;
use DuplicateAd\Constant\DACommonConstant;
use DuplicateAd\Constant\DAEndpointConstant;
use DuplicateAd\Constant\DAResponseField;
use DuplicateAd\Constant\RequestParamConstant;
use DuplicateAd\DBManager\DBServiceFacade;
use DuplicateAd\Constant\DBFieldConstant;

main();

function taskDeal($duplicateTask)
{
	foreach ($duplicateTask as $taskValue)
	{
		$taskName = $taskValue->getTaskName();
		$userId = $taskValue->getUserId();
		$id = $taskValue->getId();
		$rootPath = $taskValue->getRootPath();
		$jsonName = $taskValue->getJsonName();

		$duplicateHandler = new DuplicateHandler($rootPath, $jsonName);
		$emailMessage = $duplicateHandler->duplicateAd();
		$updateResult = DBServiceFacade::updateDuplicateTaskById(2, $id);
		if (false === $updateResult)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#updateDuplicateTaskById#Failed to update in taskDeal.');
			return false;
		}
		$queryParam = array(
			RequestParamConstant::DB_SERVICE_USER_ID => $userId
		);
		$dbResponse = HttpClient::instance()->sendRequest(DACommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET, DAEndpointConstant::USER_INFO_BY_ID, $queryParam);
		if(!ServiceHelper::checkResponse($dbResponse))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryUserInfo#Failed to check dbResponse');
			return false;
		}
		$body = $dbResponse->getBody();
		if(empty($body))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryUserInfo#Failed to get body.');
			return false;
		}
		$userData = json_decode($body, true);
		if(empty($userData))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryUserInfo#Failed to get userData.');
			return false;
		}
		$email = $userData[DAResponseField::DATA][0][DAResponseField::EMAIL];
		if(empty($email))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryUserInfo#Failed to get email.');
			return false;
		}
		$name = $userData[DAResponseField::DATA][0][DAResponseField::NAME];
		if(empty($name))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryUserInfo#Failed to get name of user.');
			return false;
		}
		MailerHelper::instance()->sendMail([$email], '[复制广告结果]' . $name . '_' . $taskName, $emailMessage);
	}
}

function main()
{
	$interval = 60 * 5;
	do{
		$duplicateTask = DBServiceFacade::getDuplicateTaskByStatus(1);
		if (false === $duplicateTask)
		{
			sleep($interval);
			continue;
		}
		taskDeal($duplicateTask);
	}while(true);

}
