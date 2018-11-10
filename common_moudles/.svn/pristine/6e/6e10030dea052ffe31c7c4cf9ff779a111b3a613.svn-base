<?php
namespace CommonMoudle\Helper;

class CommonHelper
{
    public static function filterMapByKeys($sourceArray, $keys)
    {
        $filteredInfo = array();
        foreach ($keys as $field)
        {
            if(array_key_exists($field, $sourceArray))
            {
                $filteredInfo[$field] = $sourceArray[$field];
            }
        }

        return $filteredInfo;
    }

    public static function isArrayAllEmpty($arrayData)
    {
        foreach ($arrayData as $item)
        {
            if(!empty($item))
            {
                return false;
            }
        }

        return true;
    }

    public static function getArrayValueByKey($key, $array)
    {
        if(!is_array($array))
        {
            return null;
        }
        if(array_key_exists($key, $array))
        {
            return $array[$key];
        }
        else
        {
            return null;
        }

    }

    public static function divisionOperate($dividend, $divisor)
    {
        if($divisor == 0)
        {
            return 0;
        }
        else
        {
            return $dividend/$divisor;
        }
    }

    public static function isPositiveInt($moneyAmount)
    {
        //如果不为整数，取整后为0，返回false
        if(!is_int($moneyAmount))
        {
            $convertValue = floor($moneyAmount);
            if($convertValue <= 0)
            {
                return false;
            }
        }

        return true;
    }

    public static function notSetValue($var)
    {
        if(isset($var))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function isStringEmpty($strContent)
    {
        if(is_null($strContent))
        {
            return true;
        }

        if(empty($strContent))
        {
            return true;
        }

        return false;
    }

    public static function strContains($srcString, $subString)
    {
        $position = strpos($srcString, $subString);
        if(false === $position)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function addOperate($addend, $summand)
    {
        return $addend + $summand;
    }

    public static function compareOperate($left, $right)
    {
        if($left > $right)
        {
            return 1;
        }
        else if($left < $right)
        {
            return -1;
        }
        else
        {
            return 0;
        }
    }

    public static function getClientIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        }
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $ip = "unknown";
        }

        return ($ip);
    }
}