<?php
namespace FBBasicService\Facade;

use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBParamConstant;
use FBBasicService\Manager\AdAccountManager;
use FBBasicService\Manager\AdUserManager;
use FBBasicService\Param\AdsetCreateParam;
use FBBasicService\Util\AdSetUtil;
use FBBasicService\Util\TargetingSearchUtil;

class OtherFacade
{
    public static function getReachEstimateByAccount(AdsetCreateParam $param)
    {
        $accountId = $param->getAccountId();
        $estimateParam = AdSetUtil::getReachEstimateParam($param);
        return self::getBidEstimateByAccount($accountId, $estimateParam);
    }

    public static function getBidEstimateByAccount($accountId, $estimateParam)
    {
        $deliveryParam = self::buildDeliveryParam($estimateParam);
        if(false === $estimateParam)
        {
            return false;
        }
        $estimateArray = TargetingSearchUtil::estimateReachBid($accountId, $estimateParam, $deliveryParam);
        return $estimateArray;
    }

    public static function getBidEstimateByAdset($adsetId)
    {
        $estimateArray = TargetingSearchUtil::getDeliveryEstimateByAdSet($adsetId);
        return $estimateArray;
    }

    private static function buildDeliveryParam($estimateParam)
    {
        $targeting = $estimateParam[FBParamConstant::REACH_ESTIMATE_PARAM_TARGETING];
        if(array_key_exists(FBParamConstant::REACH_ESTIMATE_PARAM_OPTIMIZATION, $estimateParam))
        {
            $optimizationGoal = $estimateParam[FBParamConstant::REACH_ESTIMATE_PARAM_OPTIMIZATION];
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The optimization goal of delivery estimate is empty');
            return false;
        }

        $deliveryParam = array();
        $deliveryParam[FBParamConstant::DELIVERY_ESTIMATE_PARAM_TARGETING] = $targeting;
        $deliveryParam[FBParamConstant::DELIVERY_ESTIMATE_PARAM_OPTIMIZATION] = $optimizationGoal;
        return $deliveryParam;
    }
















}