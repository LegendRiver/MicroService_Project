<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 上午9:52
 */

namespace FBBasicService\Facade;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBCommonConstant;
use FBBasicService\Manager\AdSetManager;

class AdsetFacade
{
    public static function createAdsetByFields($accountId, array $fields)
    {
        $adsetEntity = AdSetManager::instance()->createAdSet($accountId, $fields);
        if(!isset($adsetEntity))
        {
            //有一种异常不返回也建成功，后面在解决这个问题;估计是手动删除的原因
            //还有超时的异常要做处理
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING,'The return value of adset create is null');
            return false;
        }
        return $adsetEntity;
    }

    public static function copyAdset($adsetId, array $param=array())
    {
        $copyResult = AdSetManager::instance()->copyAdset($adsetId, $param);
        if(empty($copyResult))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING,'Failed to copy adset: ' . $adsetId);
            return false;
        }
        return $copyResult;
    }

    public static function updateAdsetName($adsetId, $name)
    {
        $updateResult = AdSetManager::instance()->updateName($adsetId, $name);
        if(false === $updateResult)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING,'Failed to update adset name: ' . $adsetId);
            return false;
        }
        return $updateResult;
    }

    public static function getAdSetIdsByParentId($parentId, $type = FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT,
                                                 $status = array(), $isClassCampaign = false)
    {
        $adsetArray = self::getAdSetEntity($parentId, $type, $status, $isClassCampaign);

        if(false === $adsetArray || CommonHelper::notSetValue($adsetArray))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'There is no ad got by '. $type . ' : ' . $parentId);
            return false;
        }

        $adsetIds = array();

        foreach($adsetArray as $adSetEntity)
        {
            $adsetIds[] = $adSetEntity->getId();
        }

        return $adsetIds;
    }

    public static function getAdSetEntity($parentId, $parentType, $status = array(), $isCampaignClass=false)
    {
        $adSetEntities = array();
        if($parentType == FBCommonConstant::INSIGHT_EXPORT_TYPE_CAMPAIGN)
        {
            $adSetEntities = AdSetManager::instance()->getAdsetsByCampaignId($parentId, $status);
        }
        else if($parentType == FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT)
        {
            if($isCampaignClass)
            {
                $adSetEntities = self::getAdsetByActHierarchy($parentId, $status);
            }
            else
            {
                $adSetEntities = AdSetManager::instance()->getAdsetsByAccountId($parentId, $status);
            }
        }

        return $adSetEntities;
    }

    private static function getAdsetByActHierarchy($accountId, $status=array())
    {
        $adSetEntities = array();
        $campaignIds = CampaignFacade::getCampaignIdsByAccount($accountId);
        if(!empty($campaignIds))
        {
            foreach ($campaignIds as $camid)
            {
                $adSets = AdSetManager::instance()->getAdsetsByCampaignId($camid, $status);
                if(empty($adSets))
                {
                    continue;
                }
                $adSetEntities = array_merge($adSetEntities, $adSets);
            }
        }

        return $adSetEntities;
    }

    public static function getAdsetById($adsetId)
    {
        $adsetEntity = AdSetManager::instance()->getAdsetById($adsetId);
        if(false === $adsetEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get adset by adsetID : ' . $adsetId);
            return false;
        }

        return $adsetEntity;
    }

    public static function getAllFieldAdsetById($adsetId)
    {
        $adset = AdSetManager::instance()->getAllFieldAdsetById($adsetId);
        if(false === $adset)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get all field adset by adsetID : ' . $adsetId);
            return false;
        }

        return $adset;
    }

    public static function getAdsetByCampaignId($campaignId)
    {
        $adsetEntities = AdSetManager::instance()->getAdsetsByCampaignId($campaignId);
        if(false === $adsetEntities)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to adset by campaignId : ' . $campaignId);
            return false;
        }

        return $adsetEntities;
    }

    public static function updateAdsetBudget($adsetId, $budgetAmount, $isDailyBudget)
    {
        return AdSetManager::instance()->updateBudget($adsetId, $isDailyBudget, $budgetAmount);
    }

    public static function updateAdSetStatus($adsetId, $status)
    {
        return AdSetManager::instance()->switchStatus($adsetId, $status);
    }
}