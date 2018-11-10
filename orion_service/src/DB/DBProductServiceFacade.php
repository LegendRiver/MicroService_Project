<?php
namespace OrionService\DB;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\ServiceHelper;
use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Logger\ServerLogger;
use OrionService\Constant\CommonConstant;
use OrionService\Constant\SendRequestParamConstant;
use OrionService\Constant\ServiceEndpointConstant;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/18
 * Time: 上午10:52
 */
class DBProductServiceFacade
{
    public static function queryValidProduct()
    {
        $dbResponse = HttpClient::instance()->sendRequest(CommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_DB_PRODUCT_INFO);

        if(!ServiceHelper::checkResponse($dbResponse))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryValidProduct#Failed to check response');
            return false;
        }

        $productData = ServiceHelper::getBodyData($dbResponse);

        return $productData;
    }

    public static function queryFBAccountByProduct($productId)
    {
        $queryParam = array(
            SendRequestParamConstant::DB_SERVICE_PRODUCT_ID => $productId
        );

        $dbResponse = HttpClient::instance()->sendRequest(CommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_DB_ACCOUNT_INFO, $queryParam);

        if(!ServiceHelper::checkResponse($dbResponse))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryFBAccountByProduct#Failed to check response');
            return false;
        }

        $accountData = ServiceHelper::getBodyData($dbResponse);

        return $accountData;
    }
}