<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/2
 * Time: 上午11:40
 */

namespace CommonMoudle\Service;


use CommonMoudle\Constant\ServiceConstant;

class ServiceParamMapManager
{
    private static $instance = null;

    private $paramMap;

    private function __construct()
    {
        $this->paramMap = array();
    }

    public static function instance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function registerParamMap($confInfo)
    {
        if(empty($confInfo))
        {
            return;
        }
        foreach ($confInfo as $item)
        {
            $this->paramMap = array_merge($this->paramMap, $item);
        }
    }

    public function getServiceName($aliasName)
    {
        return $this->getAttributeValue($aliasName, ServiceConstant::PARAM_CONF_SERVICE_NAME);
    }

    public function getClassName($aliasName)
    {
        return $this->getAttributeValue($aliasName, ServiceConstant::PARAM_CONF_CLASS_NAME);
    }

    public function getFunctionName($aliasName)
    {
        return $this->getAttributeValue($aliasName, ServiceConstant::PARAM_CONF_FUNCTION_NAME);
    }

    public function getIsAuth($aliasName)
    {
        return $this->getAttributeValue($aliasName, ServiceConstant::PARAM_CONF_IS_AUTH);
    }

    public function isExistAlias($aliasName)
    {
        return array_key_exists($aliasName, $this->paramMap);
    }

    private function getAttributeValue($aliasName, $attributeName)
    {
        if(array_key_exists($aliasName, $this->paramMap))
        {
            $info = $this->paramMap[$aliasName];
            return $info[$attributeName];
        }
        else
        {
            return null;
        }
    }
}