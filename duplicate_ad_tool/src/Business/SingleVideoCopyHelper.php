<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/7
 * Time: 下午5:11
 */

namespace DuplicateAd\Business;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Constant\ConfConstant;
use DuplicateAd\Constant\DACommonConstant;
use DuplicateAd\Constant\FBFieldConstant;
use DuplicateAd\FBManager\FBServiceFacade;

class SingleVideoCopyHelper extends SingleCopyHelper
{

    protected function getUploadMaterial($accountId, $materialPath)
    {

        return $this->uploadVideoOperation($accountId, $materialPath);
    }

    protected function buildCreative($materialId, $adName, $textInfo)
    {
        $customCreativeField = array();
        $customCreativeField[FBFieldConstant::CUSTOM_DATA_TYPE] = DACommonConstant::AD_TYPE_SINGLE_VIDEO;

        $creativeInfo = $this->temCreativeFields;
        if(!empty($adName))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_NAME] = $adName . '_creative_' . time();

        }

        $message = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_MESSAGE, $textInfo);
        $headline = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_HEADLINE, $textInfo);
        $deepLink = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_DEEP_LINK, $textInfo);

        if(!empty($message))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_BODY] = $message;
        }
        if(!empty($headline))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_TITLE] = $headline;
        }
        $creativeInfo[FBFieldConstant::CREATIVE_VIDEO_ID] = $materialId;

        $customCreativeField[FBFieldConstant::CUSTOM_MAIN_FIELD] = $creativeInfo;

        $imageUrl = FBServiceFacade::getThumbUrlOfVideo($materialId);
        $storySpec = $creativeInfo[FBFieldConstant::CREATIVE_OBJECT_STORY_SPEC];
        if(array_key_exists(FBFieldConstant::STORY_VIDEO_DATA, $storySpec))
        {
            $videoData = $storySpec[FBFieldConstant::STORY_VIDEO_DATA];
            $videoData[FBFieldConstant::VIDEO_VIDEO_ID] = $materialId;
            if(!empty($imageUrl))
            {
                $videoData[FBFieldConstant::VIDEO_IMAGE_URL] = $imageUrl;
            }
            unset($videoData[FBFieldConstant::VIDEO_IMAGE_HASH]);

            if(!empty($message))
            {
                $videoData[FBFieldConstant::VIDEO_MESSAGE] = $message;
            }
            if(!empty($headline))
            {
                $videoData[FBFieldConstant::VIDEO_TITLE] = $headline;
            }

            $callToAction = CommonHelper::getArrayValueByKey(FBFieldConstant::VIDEO_CALL_TO_ACTION, $videoData);
            $callToAction = $this->setDeepLink($callToAction, $deepLink);
            $videoData[FBFieldConstant::VIDEO_CALL_TO_ACTION] = $callToAction;

            $customCreativeField[FBFieldConstant::CUSTOM_STORY_DATA] = $videoData;
        }
        else
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#copy# There is not video data in creative.');
            return false;
        }

        return $customCreativeField;

    }
}