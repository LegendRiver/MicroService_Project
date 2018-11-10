<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 下午1:07
 */

namespace FBBasicService\Facade;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdCreativeLinkDataChildAttachmentFields;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\AdTypeConstant;
use FBBasicService\Constant\FBCommonConstant;
use FBBasicService\Manager\AdManager;

class AdFacade
{
    public static function createAdByField($accountId, $adFiledArray)
    {
        $adEntity = AdManager::instance()->createAd($accountId, $adFiledArray);
        if(false === $adEntity || !isset($adEntity))
        {
            //有一种异常不返回也创建成功，后面在解决这个问题;估计是手动删除的原因
            //还有超时的异常要做处理
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The return value of ad create is invalid : ' .
                print_r($adEntity, true));
            return false;
        }

        return $adEntity;
    }

    public static function getAdIdsByParentId($parentId, $type = FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT,
                                              $filterStatus = array(), $isHerarchy = false)
    {
        $adArray = self::getAdEntity($parentId, $type, $filterStatus, $isHerarchy);

        if(false === $adArray || CommonHelper::notSetValue($adArray))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'There is no ad got by '. $type . ' : ' . $parentId);
            return false;
        }

        $adIds = array();

        foreach($adArray as $adEntity)
        {
            $adIds[] = $adEntity->getId();
        }

        return $adIds;
    }

    public static function getAdEntity($exportId, $exportDimension, $filterStatus = array(), $isClass=false)
    {
        $adEntities = array();

        if($exportDimension == FBCommonConstant::INSIGHT_EXPORT_TYPE_CAMPAIGN)
        {
            $adEntities = AdManager::instance()->getAdsByCampaign($exportId, $filterStatus);
        }
        else if($exportDimension == FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT)
        {
            if($isClass)
            {
                $adEntities = self::getAdByActHierarchy($exportId, $filterStatus);
            }
            else
            {
                $adEntities = AdManager::instance()->getAdsByAccount($exportId, $filterStatus);
            }
        }
        else if($exportDimension == FBCommonConstant::INSIGHT_EXPORT_TYPE_ADSET)
        {
            $adEntities = AdManager::instance()->getAdsByAdSet($exportId, $filterStatus);
        }

        return $adEntities;
    }

    public static function getAdByCamHierarchy($campaignId, $filterStatus=array())
    {
        $adList = array();
        //注意ad的filterstatus 不一定适用于 adset, 此处暂时是相同的，如有不同场景，需要修改
        $adsetIds = AdsetFacade::getAdSetIdsByParentId($campaignId, FBCommonConstant::INSIGHT_EXPORT_TYPE_CAMPAIGN, $filterStatus);
        if(!empty($adsetIds))
        {
            foreach ($adsetIds as $id)
            {
                $ad = AdManager::instance()->getAdsByAdSet($id, $filterStatus);
                if(empty($ad))
                {
                    continue;
                }
                $adList = array_merge($adList, $ad);
            }
        }

        return $adList;
    }

    private static function getAdByActHierarchy($accountId, $filterStatus=array())
    {
        $adList = array();
        $campaignIds = CampaignFacade::getCampaignIdsByAccount($accountId);
        if(!empty($campaignIds))
        {
            foreach ($campaignIds as $camid)
            {
                $ad = AdManager::instance()->getAdsByCampaign($camid, $filterStatus);
                if(empty($ad))
                {
                    continue;
                }
                $adList = array_merge($adList, $ad);
            }
        }

        return $adList;
    }

    public static function getDisapprovedAdByAccount($accountId)
    {
        return AdManager::instance()->getDisapprovedAdByAccount($accountId);
    }

    public static function getAdById($adId)
    {
        $adEntity = AdManager::instance()->getAdById($adId);
        if(false === $adEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to ad by adID : ' . $adId);
            return false;
        }

        return $adEntity;
    }

    public static function updateAdStatus($adId, $status)
    {
        return AdManager::instance()->switchStatus($adId, $status);
    }

    public static function getAdType($adId)
    {
        $creativeInfo = self::getCreativeFieldsByAd($adId);
        if(false === $creativeInfo || empty($creativeInfo))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get creativeInfo by adId: ' . $adId);
            return false;
        }
        $attachment = self::getAttachment($creativeInfo);
        if(empty($attachment))
        {
            return self::getSingleType($creativeInfo);
        }
        else
        {
            return self::getCarouselType($attachment);
        }
    }

    private static function getCarouselType($attachments)
    {
        if(empty($attachments))
        {
            return AdTypeConstant::NONE_TYPE;
        }
        else
        {
            $firstChild = $attachments[0];

            //顺序不能变，因为视频也有ImageHash
            if(array_key_exists(AdCreativeLinkDataChildAttachmentFields::VIDEO_ID, $firstChild))
            {
                return AdTypeConstant::CAROUSEL_VIDEO;
            }
            else if(array_key_exists(AdCreativeLinkDataChildAttachmentFields::IMAGE_HASH, $firstChild))
            {
                return AdTypeConstant::CAROUSEL_IMAGE;
            }
            else
            {
                return AdTypeConstant::NONE_TYPE;
            }
        }
    }

    private static function getSingleType($creativeInfo)
    {
        $storySpec = CommonHelper::getArrayValueByKey(AdCreativeFields::OBJECT_STORY_SPEC, $creativeInfo);

        if(empty($storySpec))
        {
            return AdTypeConstant::NONE_TYPE;
        }

        if(array_key_exists(AdCreativeObjectStorySpecFields::LINK_DATA, $storySpec))
        {
            return AdTypeConstant::SINGLE_IMAGE;
        }
        else if(array_key_exists(AdCreativeObjectStorySpecFields::VIDEO_DATA, $storySpec))
        {
            return AdTypeConstant::SINGLE_VIDEO;
        }
        else
        {
            return AdTypeConstant::NONE_TYPE;
        }
    }

    private static function getAttachment($creativeInfo)
    {
        $storySpec = $creativeInfo[AdCreativeFields::OBJECT_STORY_SPEC];
        if(array_key_exists(AdCreativeObjectStorySpecFields::LINK_DATA, $storySpec))
        {
            $linkData = $storySpec[AdCreativeObjectStorySpecFields::LINK_DATA];
            if(array_key_exists(AdCreativeLinkDataFields::CHILD_ATTACHMENTS, $linkData))
            {
                return $linkData[AdCreativeLinkDataFields::CHILD_ATTACHMENTS];
            }
            else
            {
                return array();
            }
        }
        else
        {
            return array();
        }
    }

    private static function getCreativeFieldsByAd($adId)
    {
        $adEntity = self::getAdById($adId);
        if(false === $adEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to read ad by id: ' .$adId);
            return array();
        }
        $creativeId = $adEntity->getCreativeId();

        $creativeInfo = CreativeFacade::getAllFieldCreativeById($creativeId);
        if(false === $creativeInfo)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to read creative by id: ' .$creativeId);
            return array();
        }

        return $creativeInfo;
    }
}