<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 下午1:08
 */

namespace FBBasicService\Facade;


use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Manager\AdInsightManager;

class InsightFacade
{
    public static function getAllAdInsightByAccount($accountId, $dateSince = '', $dateUtil = '')
    {
        $adIds = AdFacade::getAdIdsByParentId($accountId);
        if(false === $adIds)
        {
            return false;
        }

        $adInsight = AdInsightManager::instance()->getAdInsightByIds($adIds, $dateSince, $dateUtil);
        if(false === $adInsight)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get the insights by adIds.');
            return false;
        }

        return $adInsight;
    }

    public static function getOneAdInsight($adId, $dateSince = '', $dateUtil = '')
    {
        $adInsight = AdInsightManager::instance()->getAdInsight($adId, $dateSince, $dateUtil);
        if(false === $adInsight)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get the insights by adId: ' . $adId);
            return false;
        }

        return $adInsight;
    }
    public static function getOneAdsetInsight($adsetId, $dateSince = '', $dateUtil = '')
    {
        $adsetInsight = AdInsightManager::instance()->getAdSetInsight($adsetId, $dateSince, $dateUtil);
        if(false === $adsetInsight)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get the insights by adsetId: ' . $adsetId);
            return false;
        }

        return $adsetInsight;
    }
    public static function getOneCampaignInsight($campaignId, $dateSince = '', $dateUtil = '')
    {
        $campaignInsight = AdInsightManager::instance()->getCampaignInsight($campaignId, $dateSince, $dateUtil);
        if(false === $campaignInsight)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get the insights by campaignId: ' . $campaignId);
            return false;
        }

        return $campaignInsight;
    }
    public static function getOneAccountInsight($accountID, $dateSince = '', $dateUtil = '')
    {
        $accountInsight = AdInsightManager::instance()->getAccountInsight($accountID, $dateSince, $dateUtil);
        if(false === $accountInsight)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get the insights by accountId: ' . $accountID);
            return false;
        }

        return $accountInsight;
    }

    public static function getFlexibleInsight($nodeId, $nodeType, $dateSince='', $dateUtil='', $insightField=array(),
                                              $otherParam=array())
    {
        $insights = AdInsightManager::instance()->getFlexibleInsight($nodeId, $nodeType, $dateSince, $dateUtil,
            $insightField, $otherParam);

        if(false === $insights)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get flexible insight by id: ' . $nodeId .
                '; node type: ' . $nodeType);
            return false;
        }

        return $insights;
    }

    public static function getAllFiledInsight($nodeId, $nodeType, $dateSince='', $dateUtil='')
    {
        $insights = AdInsightManager::instance()->getAllFieldInsight($nodeId, $dateSince, $dateUtil, $nodeType);

        if(false === $insights)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get flexible insight by id: ' . $nodeId .
                '; node type: ' . $nodeType);
            return false;
        }

        return $insights;
    }
}