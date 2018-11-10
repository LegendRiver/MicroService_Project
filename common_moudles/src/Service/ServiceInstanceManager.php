<?php
namespace CommonMoudle\Service;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;

class ServiceInstanceManager
{
    private $instanceMap = null;
    private $serviceName = null;

    public function  __construct($serviceName)
    {
        $this->serviceName = $serviceName;
        $this->instanceMap = array();
    }

    public function __destruct()
    {
        $classNames = array_keys($this->instanceMap);

        foreach($classNames as $key)
        {
            unset($this->instanceMap[$key]);
        }

        unset($this);
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function registerClassInstance($className, $classInstance)
    {
        if(array_key_exists($className, $this->instanceMap))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, sprintf("Class <%s> exists.", $className));
            return false;
        }

        $this->instanceMap[$className] = $classInstance;
        return true;
    }

    public function unregisterClassInstance($className)
    {
        if(!array_key_exists($className, $this->instanceMap))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, sprintf("Class <%s> not exists.", $className));
            return false;
        }

        unset($this->instanceMap[$className]);
        return true;
    }

    public function callClassInstance($className, $functionName, $parameters)
    {
        if(!array_key_exists($className, $this->instanceMap))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'There is not function: ' . $functionName . 'in class: ' . $className);
            return false;
        }

        $instance = $this->instanceMap[$className];
        if (method_exists($instance, $functionName))
        {
            return call_user_func_array(array($instance, $functionName), array($parameters));
        }
        else
        {
            return false;
        }
    }
}