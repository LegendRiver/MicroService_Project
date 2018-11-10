<?php
namespace FBBasicService\Util;

use FBBasicService\Constant\TargetingConstant;

class TargetingUtil
{
    public static function getBehaviorList()
    {

    }

    public static function checkAgeValid($age)
    {
        $boolResult = true;
        if(!is_int($age))
        {
            $boolResult = false;
        }

        if($age < TargetingConstant::AGE_MIN_LIMIT || $age > TargetingConstant::AGE_MAX_LIMIT)
        {
            $boolResult = false;
        }

        return $boolResult;
    }

    public static function checkGenderValid($gender)
    {
        $boolResult = false;
        $checkArray = array(TargetingConstant::GENDER_MALE, TargetingConstant::GENDER_FEMALE);
        if(is_int($gender))
        {
            if(in_array($gender, $checkArray, true))
            {
                $boolResult = true;
            }
        }
        else if(is_array($gender))
        {
            $uniqueArray = array_unique($gender);
            $acount = count($uniqueArray);
            if($acount <= 2 && $acount > 0)
            {
                $diffArray = array_diff($gender, $checkArray);
                if(0 == count($diffArray))
                {
                    $boolResult = true;
                }
            }
        }
        else
        {
            $boolResult = false;
        }

        return $boolResult;
    }

}