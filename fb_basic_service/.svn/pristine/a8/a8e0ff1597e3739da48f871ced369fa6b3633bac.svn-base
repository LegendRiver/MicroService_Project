<?php
namespace FBBasicService\Common;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\JsonFileHelper;
use FacebookAds\Api;
use FBBasicService\Constant\FBAPIConstant;

class FBAPIManager
{
    private static $instance=null;

    private $apiMap = array();

    private function __construct()
    {
        $apiConfFile = FBPathManager::instance()->getConfPath(). DIRECTORY_SEPARATOR . 'api_conf.json';
        $configInfo = JsonFileHelper::readJsonFile($apiConfFile);
        $confList = $configInfo[FBAPIConstant::CONF_LIST];
        foreach ($confList as $conf)
        {
            $key = $conf[FBAPIConstant::KEY];
            $id = $conf[FBAPIConstant::ID];
            $token = $conf[FBAPIConstant::TOKEN];
            $secret = $conf[FBAPIConstant::SECRET];

            Api::init($id, $secret, $token);
            $apiInstance = Api::instance();
            $this->apiMap[$key] = $apiInstance;
        }

        $defaultInstance = $this->apiMap[FBAPIConstant::KEY_DON];
        Api::setInstance($defaultInstance);
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function changeApi($apiKey = FBAPIConstant::KEY_DON)
    {
        if(array_key_exists($apiKey, $this->apiMap))
        {
           $instance = $this->apiMap[$apiKey];
           Api::setInstance($instance);
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to find api instance by key: ' . $apiKey);
        }
    }

}