<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/25
 * Time: 下午5:22
 */

namespace FBBasicService\Facade;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FBBasicService\Common\FBLogger;
use FBBasicService\Manager\AdCampaignManager;

class CampaignFacade
{
    public static function createMediaCampaign($adAccountId, $fieldArray)
    {
        $campaignManager = AdCampaignManager::instance();
        $newCampaign = $campaignManager->createCampaign($adAccountId, $fieldArray);
        if(!isset($newCampaign))
        {
            //有一种异常不返回也创建成功，后面在解决这个问题;估计是手动删除的原因
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,'The return value of campaign create is null');
            return false;
        }

        return $newCampaign;
    }

    public static function getCampaignIdsByAccount($accountId)
    {
        $campaignEntityArray = AdCampaignManager::instance()->getCampaignByAccount($accountId);

        if (false === $campaignEntityArray || CommonHelper::notSetValue($campaignEntityArray))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'There is no campaign got by accountID: ' . $accountId);
            return false;
        }

        $campaignIds = array();

        foreach ($campaignEntityArray as $campaignEntity)
        {
            $campaignIds[] = $campaignEntity->getCampaignId();
        }

        return $campaignIds;
    }

    public static function getCampaignById($campaignId)
    {
        $campaignEntity = AdCampaignManager::instance()->getCampaignById($campaignId);
        if(false === $campaignEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get campaign by campaignId : ' . $campaignId);
            return false;
        }

        return $campaignEntity;
    }

    public static function deleteCampaign($campaignId)
    {
        return AdCampaignManager::instance()->deleteCampaign($campaignId);
    }

    public static function updateCampaignStatus($campaignId, $status)
    {
        return AdCampaignManager::instance()->switchStatus($campaignId, $status);
    }
}