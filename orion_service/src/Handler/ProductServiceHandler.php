<?php
namespace OrionService\Handler;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Logger\ServerLogger;
use OrionService\FB\FBServiceFacade;
use OrionService\Constant\InField\DBAccountField;
use OrionService\Constant\InField\FBAccountField;
use OrionService\Constant\OutField\ProductField;
use OrionService\DB\DBProductServiceFacade;

class ProductServiceHandler
{
    public static function getAccountData($productId)
    {
        $accountList = DBProductServiceFacade::queryFBAccountByProduct($productId);
        if(false === $accountList)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                'Failed to query account info in db by productId:' . $productId);
            return false;
        }

        $accountInfo = array();
        $accountInfo[ProductField::ACCOUNT_BASIC_TITLE] = ProductField::$accountBasicTitle;
        $actData = array();
        foreach ($accountList as $account)
        {
            $id = CommonHelper::getArrayValueByKey(DBAccountField::ACCOUNT_ID, $account);
            $accountId = CommonHelper::getArrayValueByKey(DBAccountField::FB_ACCOUNT_ID, $account);
            $name = CommonHelper::getArrayValueByKey(DBAccountField::ACCOUNT_NAME, $account);
            $agency = CommonHelper::getArrayValueByKey(DBAccountField::ACCOUNT_AGENCY, $account);

            $fbAccount = FBServiceFacade::queryAccountById($accountId);
            $cap = 0;
            $totalSpend = 0;
            if(false !== $fbAccount)
            {
                $name = CommonHelper::getArrayValueByKey(FBAccountField::ACCOUNT_NAME, $fbAccount);
                $cap = CommonHelper::getArrayValueByKey(FBAccountField::SPEND_CAP, $fbAccount);
                $totalSpend = CommonHelper::getArrayValueByKey(FBAccountField::SPEND_AMOUNT, $fbAccount);
            }
            else
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING,
                    'Failed to query account info from fb by accountId: ' . $accountId);
            }

            $actData[$id] = array($accountId, $name, $agency, $cap/100, $totalSpend/100);
        }
        $accountInfo[ProductField::ACCOUNT_BASIC_DATA] = $actData;

        return $accountInfo;
    }

    public static function queryAccountGEOInsight($productId, $startDate, $endDate)
    {
        $accountList = DBProductServiceFacade::queryFBAccountByProduct($productId);
        if(false === $accountList)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                'Failed to query account info in db by productId:' . $productId);
            return false;
        }

        $accountPerformanceData = array();
        foreach ($accountList as $accountInfo)
        {
            $id = CommonHelper::getArrayValueByKey(DBAccountField::ACCOUNT_ID, $accountInfo);
            $accountId = CommonHelper::getArrayValueByKey(DBAccountField::FB_ACCOUNT_ID, $accountInfo);
            $insightValue = FBServiceFacade::queryAccountGeoInsight($accountId, $startDate, $endDate);
            if(empty($insightValue))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING,
                    'The insight value of account  is empty. accountId: ' . $accountId);
                continue;
            }

            $accountPerformanceData[$id] = static::preHandleData($insightValue);
        }

        $actCountryData = array();
        $actCountryData[ProductField::ACCOUNT_PERFORMANCE_TITLE] = ProductField::$accountPerformanceTitle;
        $actCountryData[ProductField::ACCOUNT_PERFORMANCE_DATA] = $accountPerformanceData;

        return $actCountryData;
    }

    private static function preHandleData($insightData)
    {
        $handledData = array();
        $mapFun = function($value)
        {
            if(empty($value))
            {
                return '0';
            }
            else
            {
                return $value;
            }
        };

        foreach ($insightData as $rowData)
        {
            $handledRow = array_map($mapFun, $rowData);
            $handledData[] = $handledRow;
        }

        return $handledData;
    }
}