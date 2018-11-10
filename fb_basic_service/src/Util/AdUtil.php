<?php
namespace FBBasicService\Util;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FacebookAds\Object\Ad;
use FBBasicService\Builder\AdFieldBuilder;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBParamValueConstant;
use FBBasicService\Param\AdCreateParam;

class AdUtil
{
    public static function transformAdField(AdCreateParam $param)
    {
        $builder = new AdFieldBuilder();

        $builder->setName($param->getName());
        $builder->setAdsetId($param->getAdsetId());
        $builder->setCreativeId($param->getCreativeId());

        $status = self::getAdStatus($param->getStatus());
        $builder->setStatus($status);

        return $builder->getOutputField();
    }

    private static function getAdStatus($status)
    {
        $resultStatus = Ad::STATUS_ACTIVE;
        if(CommonHelper::notSetValue($status))
        {
            return $resultStatus;
        }

        if(FBParamValueConstant::PARAM_STATUS_ACTIVE == $status)
        {
            $resultStatus = Ad::STATUS_ACTIVE;
        }
        else if(FBParamValueConstant::PARAM_STATUS_PAUSED == $status)
        {
            $resultStatus = Ad::STATUS_PAUSED;
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Only ACTIVE and PAUSED are valid during creation');
        }

        return $resultStatus;
    }
}