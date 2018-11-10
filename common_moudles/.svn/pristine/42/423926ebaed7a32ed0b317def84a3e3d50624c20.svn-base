<?php
namespace CommonMoudle\Service;
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/29
 * Time: 下午5:18
 */
class ServiceManager
{
    private static $instance = null;

    private $serviceName2Instance;

    private $className2Function;

    private function __construct()
    {
        $this->serviceName2Instance = array();
        $this->className2Function = array();
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function callService($serviceName, $className, $functionName, $parameters)
    {
        if(!$this->checkFunctionExporting($className, $functionName))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#service# The function ' . $functionName .
                ' of class ' . $className . 'is not exporting.');
            return false;
        }

        if(!array_key_exists($serviceName, $this->serviceName2Instance))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#service# The service ' . $serviceName .
                ' did not register.' );
            return false;
        }
        return $this->serviceName2Instance[$serviceName]->callClassInstance($className, $functionName, $parameters);
    }

    public function registerClassInstance($serviceName, $className, $exportFunctions=array())
    {
        if(!array_key_exists($serviceName, $this->serviceName2Instance))
        {
            $this->serviceName2Instance[$serviceName] = new ServiceInstanceManager($serviceName);
        }

        $service = $this->serviceName2Instance[$serviceName];
        $reflectInstance = new \ReflectionClass($className);
        $instance = $reflectInstance->newInstance();
        if (!$service->registerClassInstance($className, $instance))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, sprintf("Register class <%s> failed.", $className));
            return false;
        }
        else
        {
            if(array_key_exists($className, $this->className2Function))
            {
                $existingFunctions = $this->className2Function[$className];
                $this->className2Function[$className] = array_unique( array_merge($existingFunctions, $exportFunctions));
            }
            else
            {
                $this->className2Function[$className] = $exportFunctions;
            }
        }

        return true;
    }

    private function checkFunctionExporting($className, $functionName)
    {
        if(!array_key_exists($className, $this->className2Function))
        {
            return false;
        }

        $functionList = $this->className2Function[$className];

        return in_array($functionName, $functionList);

    }
}