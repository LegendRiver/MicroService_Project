<?php

namespace DuplicateAd\FBManager;

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

class FBServiceFacade
{
    public static function getAdsetAllField($adsetId)
    {
        $queryParam = array(RequestParamConstant::FB_SERVICE_ADSET_ID => $adsetId);

        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::QUERY_ADSET_FIELD, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getAdsetAllField#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adsetField = CommonHelper::getArrayValueByKey(DAResponseField::ADSET_ALL_FIELD, $bodyData);
        return $adsetField;
    }

    public static function getCreativeField($adId)
    {
        $queryParam = array(RequestParamConstant::FB_SERVICE_AD_ID => $adId);

        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::QUERY_CREATIVE_FIELD, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getCreativeField#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $creativeField = CommonHelper::getArrayValueByKey(DAResponseField::CREATIVE_ALL_FIELD, $bodyData);
        return $creativeField;
    }

    public static function getFirstAdId($adsetId)
    {
        $queryParam = array(RequestParamConstant::FB_SERVICE_ADSET_ID => $adsetId);

        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::QUERY_FB_FIRST_AD, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getFirstAdId#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adId = CommonHelper::getArrayValueByKey(DAResponseField::FIRST_AD_ID, $bodyData);
        return $adId;
    }

    public static function getAdType($adId='')
    {
        if(empty($adId))
        {
            return DACommonConstant::AD_TYPE_NONE_TYPE;
        }

        $queryParam = array(RequestParamConstant::FB_SERVICE_AD_ID => $adId);
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::QUERY_FB_AD_TYPE, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getAdType#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adType = CommonHelper::getArrayValueByKey(DAResponseField::AD_TYPE, $bodyData);

        return $adType;
    }

    public static function uploadImage($accountId, $materialPath)
    {
        if(empty($accountId) || empty($materialPath))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                'The param of uploadImage is empty, accountId: ' . $accountId . '; $materialPath: ' . $materialPath);
            return array();
        }

        $queryParam = array(
            RequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId,
            RequestParamConstant::FB_SERVICE_MATERIAL_PATH => $materialPath,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::UPLOAD_IMAGE, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadImage#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);

        return $bodyData;
    }

    public static function uploadVideo($accountId, $materialPath)
    {
        if(empty($accountId) || empty($materialPath))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                'The param of uploadVideo is empty, accountId: ' . $accountId . '; $materialPath: ' . $materialPath);
            return array();
        }

        $queryParam = array(
            RequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId,
            RequestParamConstant::FB_SERVICE_MATERIAL_PATH => $materialPath,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::UPLOAD_VIDEO, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadVideo#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);

        return $bodyData;
    }

    public static function copyAdsetByFields($accountId, $adsetFields)
    {
        if(empty($accountId) || empty($adsetFields))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                'The param of copyAdsetByFields is empty, accountId: ' . $accountId . '; fields: ' . print_r($adsetFields, true));
            return false;
        }

        $postParam = array(
            RequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId,
            RequestParamConstant::FB_SERVICE_ADSET_FIELD => $adsetFields,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_POST,
            DAEndpointConstant::CREATE_ADSET, array(), $postParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#copyAdsetByFields#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adsetId = CommonHelper::getArrayValueByKey(DAResponseField::ADSET_ID, $bodyData);
        return $adsetId;
    }

    public static function copyAdsetByCopyAPI($temAdsetId, $newAdsetName='', $toCampaignId='')
    {
        if(empty($temAdsetId))
        {
            ServerLogger::instance()->writelog(LogConstant::LOGGER_LEVEL_ERROR,
                'The param temAdsetId of copyAdsetByCopyAPI is empty');
            return false;
        }

        $queryParam = array(
            RequestParamConstant::FB_SERVICE_BASE_ADSET_ID => $temAdsetId,
            RequestParamConstant::FB_SERVICE_NEW_ADSET_NAME => $newAdsetName,
            RequestParamConstant::FB_SERVICE_TO_CAMPAIGN_ID => $toCampaignId,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::DUPLICATE_ADSET, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#copyAdsetByCopyAPI#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adsetId = CommonHelper::getArrayValueByKey(DAResponseField::ADSET_ID, $bodyData);
        return $adsetId;
    }

    public static function getThumbUrlOfVideo($videoId)
    {
        if(empty($videoId))
        {
            ServerLogger::instance()->writelog(LogConstant::LOGGER_LEVEL_ERROR,
                'The param videoId of getThumbUrlOfVideo is empty');
            return false;
        }

        $queryParam = array(
            RequestParamConstant::FB_SERVICE_VIDEO_ID => $videoId,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_GET,
            DAEndpointConstant::FIRST_VIDEO_THUMB, $queryParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getThumbUrlOfVideo#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $thumbUrl = CommonHelper::getArrayValueByKey(DAResponseField::THUMB_URL, $bodyData);
        return $thumbUrl;
    }

    public static function createAdByCreative($accountId, $adsetId, $adName, $creativeField)
    {
        $postParam = array(
            RequestParamConstant::FB_SERVICE_ACCOUNT_ID => $accountId,
            RequestParamConstant::FB_SERVICE_ADSET_ID => $adsetId,
            RequestParamConstant::FB_SERVICE_AD_NAME => $adName,
            RequestParamConstant::FB_SERVICE_CREATIVE_FIELD => $creativeField,
        );
        $response = HttpClient::instance()->sendRequest(DACommonConstant::FB_SERVER_KEY, RequestInfo::METHOD_POST,
            DAEndpointConstant::CREATE_AD_BY_CREATIVE, array(), $postParam);

        if(!ServiceHelper::checkResponse($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdByCreative#Failed to check response');
            return false;
        }

        $bodyData = ServiceHelper::getBodyData($response);
        $adId = CommonHelper::getArrayValueByKey(DAResponseField::AD_ID, $bodyData);
        return $adId;
    }
}