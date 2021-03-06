<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/25
 * Time: 上午10:08
 */

namespace CommonMoudle\Helper;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Service\ServiceBaseConstant;

class ServiceHelper
{
    public static function checkResponse($response)
    {
        if(false === $response)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The server config is wrong. Check server ip and port.');
            return false;
        }

        if(empty($response))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The response is empty');
            return false;
        }

        $responseData = JsonFileHelper::decodeJsonString($response->getBody());
        if(false === $responseData)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to parse json string of response body');
            return false;
        }

        $stateCode = CommonHelper::getArrayValueByKey(ServiceBaseConstant::BODY_STATE_CODE, $responseData);
        if($stateCode != ServiceBaseConstant::OK)
        {
            $message = CommonHelper::getArrayValueByKey(ServiceBaseConstant::BODY_MESSAGE, $responseData);
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'ErrorCode: ' . $stateCode . '; message: ' . $message);
            return false;
        }

        return true;
    }

    public static function getBodyData($response)
    {
        $responseData = JsonFileHelper::decodeJsonString($response->getBody());
        $bodyData = CommonHelper::getArrayValueByKey(ServiceBaseConstant::BODY_DATA, $responseData);
        return $bodyData;
    }
}