<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/7
 * Time: 下午5:10
 */

namespace DuplicateAd\Business;



use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Constant\ConfConstant;
use DuplicateAd\Constant\DACommonConstant;
use DuplicateAd\Constant\FBFieldConstant;

class SingleImageCopyHelper extends SingleCopyHelper
{
    protected function getUploadMaterial($accountId, $materialPath)
    {
        return $this->uploadImageOperation($accountId, $materialPath);
    }

    protected function buildCreative($materialId, $adName, $textInfo)
    {
        $customCreativeField = array();
        $customCreativeField[FBFieldConstant::CUSTOM_DATA_TYPE] = DACommonConstant::AD_TYPE_SINGLE_IMAGE;

        $creativeInfo = $this->temCreativeFields;
        if(!empty($adName))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_NAME] = $adName . '_creative_' . time();
        }
        $message = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_MESSAGE, $textInfo);
        $headline = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_HEADLINE, $textInfo);
        $deepLink = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_DEEP_LINK, $textInfo);
        $creativeInfo[FBFieldConstant::CREATIVE_IMAGE_HASH] = $materialId;
        if(!empty($message))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_BODY] = $message;
        }
        if(!empty($headline))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_TITLE] = $headline;
        }
        $customCreativeField[FBFieldConstant::CUSTOM_MAIN_FIELD] = $creativeInfo;

        $storySpec = $creativeInfo[FBFieldConstant::CREATIVE_OBJECT_STORY_SPEC];
        if(array_key_exists(FBFieldConstant::STORY_LINK_DATA, $storySpec))
        {
            $linkData = $storySpec[FBFieldConstant::STORY_LINK_DATA];
            $linkData[FBFieldConstant::LINK_IMAGE_HASH] = $materialId;
            if(!empty($message))
            {
                $linkData[FBFieldConstant::LINK_MESSAGE] = $message;
            }
            if(!empty($headline))
            {
                $linkData[FBFieldConstant::LINK_NAME] = $headline;
            }

            $callToAction = CommonHelper::getArrayValueByKey(FBFieldConstant::LINK_CALL_TO_ACTION, $linkData);
            $callToAction = $this->setDeepLink($callToAction, $deepLink);
            $linkData[FBFieldConstant::LINK_CALL_TO_ACTION] = $callToAction;

            $customCreativeField[FBFieldConstant::CUSTOM_STORY_DATA] = $linkData;
        }
        else
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#copy# There is not link data in creative.');
            return false;
        }

        return $customCreativeField;
    }
}