<?php
namespace OrionService\FB;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/18
 * Time: 上午10:45
 */
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\ServiceHelper;
use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Logger\ServerLogger;
use OrionService\Constant\CommonConstant;
use OrionService\Constant\InField\FBInsightField;
use OrionService\Constant\InField\FBOtherField;
use OrionService\Constant\SendRequestParamConstant;
use OrionService\Constant\ServiceEndpointConstant;

/**
 * Class FBServiceFacade
 * 目前只支持单服务器调用服务，后续需要多服务要重构调用服务，智能选择服务器
 */
class FBServiceFacade
{
    public static function queryAccountById($accountId)
    {
        $queryParam = array(
            SendRequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId
        );

        $response = HttpClient::instance()->sendRequest(CommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_FB_ACCOUNT_INFO, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryAccountById#Failed to check response');
            return false;
        }

        $accountData = ServiceHelper::getBodyData($response);

        return $accountData;
    }

    public static function queryAccountGeoInsight($accountId, $startDate, $endDate)
    {
        $queryParam = array(
            SendRequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId,
            SendRequestParamConstant::FB_SERVICE_START_DATE => $startDate,
            SendRequestParamConstant::FB_SERVICE_END_DATE => $endDate,
        );

        $insightResponse = HttpClient::instance()->sendRequest(CommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_FB_ACCOUNT_GEO_INSIGHT, $queryParam);

        if(!ServiceHelper::checkResponse($insightResponse))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryAccountGeoInsight#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($insightResponse);
        $insightData = CommonHelper::getArrayValueByKey(FBInsightField::INSIGHT_DATA, $bodyData);

        return $insightData;
    }

    public static function getAdType($adsetId, $adId='')
    {
        if(empty($adId))
        {
            $adId = self::getFirstAdId($adsetId);
            if(empty($adId))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The first ad id is empty by adsetId: ' . $adsetId);
                return false;
            }
        }

        $queryParam = array(SendRequestParamConstant::FB_SERVICE_AD_ID => $adId);
        $response = HttpClient::instance()->sendRequest(CommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_FB_AD_TYPE, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getAdType#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adType = CommonHelper::getArrayValueByKey(FBOtherField::AD_TYPE, $bodyData);

        return $adType;
    }

    private static function getFirstAdId($adsetId)
    {
        $queryParam = array(SendRequestParamConstant::FB_SERVICE_ADSET_ID => $adsetId);

        $response = HttpClient::instance()->sendRequest(CommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            ServiceEndpointConstant::QUERY_FB_FIRST_AD, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getFirstAdId#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adId = CommonHelper::getArrayValueByKey(FBOtherField::FIRST_AD_ID, $bodyData);
        return $adId;
    }
}