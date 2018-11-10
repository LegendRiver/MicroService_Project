<?php
namespace CommonMoudle\Service;
use CommonMoudle\Constant\ServiceConstant;
use CommonMoudle\Helper\JsonFileHelper;


class ServiceInitializer
{
    public static function exportService($serviceConfigFile)
    {
        $serviceInfo = self::parseServiceConfig($serviceConfigFile);
        self::registerServices($serviceInfo);
    }

    public static function initRequestParamMap($paramMapFile)
    {
        $paramMap = self::parseParamMapConf($paramMapFile);
        ServiceParamMapManager::instance()->registerParamMap($paramMap);
    }

    private static function registerServices($serviceMap)
    {
        foreach ($serviceMap as $serviceName=>$classInfo)
        {
            foreach ($classInfo as $className=>$functionList)
            {
                ServiceManager::instance()->registerClassInstance($serviceName, $className, $functionList);
            }
        }
    }

    private static function parseServiceConfig($configFile)
    {
        $configInfo = JsonFileHelper::readJsonFile($configFile);
        $configList = $configInfo[ServiceConstant::CONF_ROOT];
        $serviceConfigMap = array();
        foreach ($configList as $item)
        {
            $serviceName = $item[ServiceConstant::CONF_SERVICE_NAME];
            $classInfo = $item[ServiceConstant::CONF_CLASS_INFO];
            $class2Function = array();
            foreach ($classInfo as $classConfig)
            {
                $className = $classConfig[ServiceConstant::CONF_CLASS_NAME];
                $functionList = $classConfig[ServiceConstant::CONF_FUNCTION_LIST];
                $class2Function[$className] = $functionList;
            }
            $serviceConfigMap[$serviceName] = $class2Function;
        }

        return $serviceConfigMap;
    }

    private static function parseParamMapConf($configFile)
    {
        $configInfo = JsonFileHelper::readJsonFile($configFile);
        return $configInfo[ServiceConstant::PARAM_CONF_ROOT];
    }
}