<?php

namespace OrionService\Handler;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\FileHelper;
use CommonMoudle\Helper\JsonFileHelper;
use OrionService\Constant\MaterialValidConstant;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Service\ServiceBaseConstant;
use OrionService\Constant\FBResponseConstant;
use OrionService\Constant\OrionServiceStateCode;
use OrionService\FB\FBServiceFacade;

class MaterialValidHandler
{
	public static function validZipFile($path)
	{
		$jsonCountJudge = self::jsonCountJudge($path);
		if (ServiceBaseConstant::OK != $jsonCountJudge)
		{
			return $jsonCountJudge;
		}
		$isExcelExist = self::isExcelExist($path);
		if (ServiceBaseConstant::OK != $isExcelExist)
		{
			return $isExcelExist;
		}
		return self::mainDeal($path);
	}

	private static function judgeAccountIdCampaignId($configInfo)
	{
		$accountId = CommonHelper::getArrayValueByKey(MaterialValidConstant::TO_ACCOUNT_ID, $configInfo);
		$campaignId = CommonHelper::getArrayValueByKey(MaterialValidConstant::TO_CAMPAIGN_ID, $configInfo);
		if (!empty($accountId) && empty($campaignId))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'If toAccountId is not null, toCampaignId is needed not null.');
			return OrionServiceStateCode::TOACCOUNTID_TOCAMPAIGNID_VALID_ERROR;
		}
		return ServiceBaseConstant::OK;
	}

	private static function ifIsShareMaterialFalse($path, $configInfo)
	{
		$materialPath = CommonHelper::getArrayValueByKey(MaterialValidConstant::MATERIALPATH, $configInfo);
		if (empty($materialPath))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The key materialPath in json file is not allowed to be null while the key isShareMaterial values false.');
			return OrionServiceStateCode::ISSHAREMATERIAL_MATERIALPATH_VALID_ERROR;
		}
		$rootMaterialPath = $path . DIRECTORY_SEPARATOR . $materialPath;
		$adsetId = CommonHelper::getArrayValueByKey(MaterialValidConstant::FROMADSETID, $configInfo);
		$adId = CommonHelper::getArrayValueByKey(MaterialValidConstant::FROMADID, $configInfo);
		$type = FBServiceFacade::getAdType($adsetId, $adId);

		switch ($type)
		{
			case FBResponseConstant::CAROUSEL_VIDEO:
			case FBResponseConstant::CAROUSEL_IMAGE:
				$dirList = FileHelper::getSubDirList($rootMaterialPath);
				if (empty($dirList))
				{
					ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The dirList in the directory is null, maybe there is not any materials.');
					return OrionServiceStateCode::NO_DIRECTORY_EXIST_ERROR;
				}
				$fileLists = FileHelper::getFileList($rootMaterialPath);
				if (!empty($fileLists))
				{
					ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The carousel types could not contain single file in given path.');
					return OrionServiceStateCode::CARROUSEL_TYPE_VALID_ERROR;
				}
				return ServiceBaseConstant::OK;
			case FBResponseConstant::VIDEO:
			case FBResponseConstant::IMAGE:
				return ServiceBaseConstant::OK;
			case FBResponseConstant::NONE:
				ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'There is no advertisement exist.');
				return OrionServiceStateCode::NO_AD_FILES_EXIST;
		}
	}

	private static function jsonCountJudge($path)
	{
		$fileList = FileHelper::getFileList($path);
		if (empty($fileList))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get fileList fail from ' . $path);
			return OrionServiceStateCode::GET_FILE_LIST_FAIL;
		}
		$jsonFileCount = 0;

		foreach ($fileList as $file)
		{
			if (substr($file, -5) == MaterialValidConstant::JSON_EXTENTION)
			{
				$jsonFileCount++;
			}
		}
		if (1 != $jsonFileCount)
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Json file is not equals one in.' . $path);
			return OrionServiceStateCode::JSON_COUNT_NOT_EQUALS_ONE;
		}
		return ServiceBaseConstant::OK;
	}

	private static function getJsonPath($path)
	{
		$fileList = FileHelper::getFileList($path);
		if (empty($fileList))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get fileList fail from ' . $path);
			return OrionServiceStateCode::GET_FILE_LIST_FAIL;
		}
		$jsonPath = '';
		foreach ($fileList as $file)
		{
			if (substr($file, -5) == MaterialValidConstant::JSON_EXTENTION)
			{
				$jsonPath = $file;
			}
		}
		return $jsonPath;
	}

	public static function getJsonName($path)
	{
		$jsonPath = self::getJsonPath($path);
		if ('' !== $jsonPath)
		{
			$jsonName = basename($jsonPath);
		}
		return $jsonName;
	}

	private static function getConfigInfos($path)
	{
		$jsonData = self::getJsonData($path);
		if (empty($jsonData))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get jsonData in method getConfigInfos fail from ' . $path);
			return OrionServiceStateCode::GET_JSON_DATA_FAIL;
		}
		if (empty(MaterialValidConstant::CONFIGINFOS))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get the key configInfos fail from the json file.');
			return OrionServiceStateCode::GET_KEY_CONFIGINFOS_FAIL;
		}
		$configInfos = CommonHelper::getArrayValueByKey(MaterialValidConstant::CONFIGINFOS, $jsonData);
		if (empty($configInfos))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'GetconfigInfos fail from the json file in method getConfigInfos.');
			return OrionServiceStateCode::GET_CONFIGINFOS_FAIL;
		}
		return $configInfos;
	}

	private static function getJsonData($path)
	{
		$jsonPath = self::getJsonPath($path);
		if (empty($jsonPath))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get jsonPath in method getJsonData fail from ' . $path);
			return OrionServiceStateCode::GET_JSON_PATH_FAIL;
		}
		$jsonData = JsonFileHelper::readJsonFile($jsonPath);
		if (empty($jsonData))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get jsonData in method getJsonData fail from ' . $path);
			return OrionServiceStateCode::GET_JSON_DATA_FAIL;
		}
		return $jsonData;
	}

	private static function isExcelExist($path)
	{
		$jsonPath = self::getJsonPath($path);
		if (empty($jsonPath))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get jsonPath fail from ' . $path);
			return OrionServiceStateCode::GET_JSON_PATH_FAIL;
		}
		$jsonData = JsonFileHelper::readJsonFile($jsonPath);
		if (empty($jsonData))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get jsonData fail from ' . $path);
			return OrionServiceStateCode::GET_JSON_DATA_FAIL;
		}
		$excelPath = $jsonData[MaterialValidConstant::CSVTEXTPATH];
		if (empty($excelPath))
		{
			return ServiceBaseConstant::OK;
		}
		$csvTextPath = $path . DIRECTORY_SEPARATOR . $excelPath;
		if (!is_file($csvTextPath))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The file type is not excel in ' . $path);
			return OrionServiceStateCode::EXCEL_TYPE_VALID_FAIL;
		}
		return ServiceBaseConstant::OK;
	}

	public static function judgeFromAdsetId($configInfo)
	{
		if (empty(CommonHelper::getArrayValueByKey(MaterialValidConstant::FROMADSETID, $configInfo)))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The key fromAdsetId in json file is not allowed to be null.');
			return OrionServiceStateCode::FROM_ADSET_ID_EMPTY;
		}
		return ServiceBaseConstant::OK;
	}

	private static function mainDeal($path)
	{
		$configInfos = self::getConfigInfos($path);
		if (empty($configInfos))
		{
			ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get configinfos fail from ' . $path);
			return OrionServiceStateCode::GET_CONFIGINFOS_FAIL;
		}
		foreach ($configInfos as $configInfo)
		{
			if (ServiceBaseConstant::OK != self::judgeFromAdsetId($configInfo))
			{
				ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get judgeFromAdsetId not equals 200 from ' . $path);
				return OrionServiceStateCode::GET_JUDGEFROMADSETID_FAIL;
			}
			if (ServiceBaseConstant::OK != self::judgeAccountIdCampaignId($configInfo))
			{
				ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Get judgeAccountIdCampaignId not equals 200 from ' . $path);
				return OrionServiceStateCode::GET_JUDGEACCOUNTIDCAMPAIGNID_FAIL;
			}
			if (false === $configInfo[MaterialValidConstant::ISSHAREMATERIAL])
			{
				$ifIsShareMaterialFalse = self::ifIsShareMaterialFalse($path, $configInfo);
				if (ServiceBaseConstant::OK == $ifIsShareMaterialFalse)
				{
					continue;
				}
				return $ifIsShareMaterialFalse;
			}
		}
		return ServiceBaseConstant::OK;
	}
}