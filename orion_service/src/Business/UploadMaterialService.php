<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/31
 * Time: 下午9:23
 */

namespace OrionService\Business;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Service\ServiceBaseConstant;
use OrionService\Common\OrionServiceResult;
use OrionService\Constant\OrionServiceStateCode;
use OrionService\Constant\QueryParamConstant;
use OrionService\DB\DBDuplicateTaskServiceFacade;
use OrionService\Handler\MaterialValidHandler;
use OrionService\Handler\UploadMaterialServiceHandler;

class UploadMaterialService
{
	public function receiveMaterialZip($param)
	{
		$response = new OrionServiceResult();

		$uploadHandler = new UploadMaterialServiceHandler();
		$statusCode = $uploadHandler->checkQueryParam($param);
		if(ServiceBaseConstant::OK != $statusCode)
		{
			$response->setErrorCode($statusCode);
			return $response;
		}

		$fileData = $param[QueryParamConstant::UPLOAD_FILE_DATA];
		$fileName = $param[QueryParamConstant::UPLOAD_POST_FILE_NAME];
		$saveFile = $uploadHandler->saveFile($fileData, $fileName);
		if(false === $saveFile)
		{
			$response->setErrorCode(OrionServiceStateCode::UPLOAD_SAVE_FILE_FAIL);
			return $response;
		}
		else
		{
			unset($param[QueryParamConstant::UPLOAD_FILE_DATA]);
		}

		$userName = $param[QueryParamConstant::UPLOAD_USER_NAME];
		$userID = $param[QueryParamConstant::UPLOAD_USER_ID];
		$taskName = $param[QueryParamConstant::UPLOAD_TASK_NAME];
		$unzipDir = $uploadHandler->unzipFile($saveFile, $userName, $userID, $taskName);
		if(false === $unzipDir)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Orion_service UploadMaterialService unzipDir false: '. $saveFile);
			$response->setErrorCode(OrionServiceStateCode::UPLOAD_UNZIP_FAIL);
			return $response;
		}

		//校验解压文件
		$stateCode = MaterialValidHandler::validZipFile($unzipDir);

		if (ServiceBaseConstant::OK != $stateCode)
		{
			$response->setErrorCode($stateCode);
			return $response;
		}
		//将数据插入数据库
		$jsonName = MaterialValidHandler::getJsonName($unzipDir);

		if (empty($jsonName))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Orion_service UploadMaterialService MaterialValidHandler:  getJsonName jsonName empty.');
			$response->setErrorCode(OrionServiceStateCode::GET_JSON_NAME_FAILED);
			return $response;
		}
		if (ServiceBaseConstant::OK == $stateCode)
		{
			$insertResult = DBDuplicateTaskServiceFacade::duplicateTaskInsert($taskName, $userID, $unzipDir, $jsonName);
			if (false === $insertResult)
			{
				ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Orion_service UploadMaterialService duplicateTaskInsert insertResult false.');
				$response->setErrorCode(OrionServiceStateCode::INSERT_DUPLICATE_TASK_FAILED);
				return $response;
			}
		}
		return $response;
	}
}