<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/7
 * Time: 下午3:50
 */

namespace FBBasicService\Business\Node;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\AdCreativeVideoData;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\ServiceConstant\FBCommonConstant;
use FBBasicService\Constant\ServiceConstant\ImageResField;
use FBBasicService\Constant\ServiceConstant\QueryParamConstant;
use FBBasicService\Constant\ServiceConstant\VideoResField;

class NodeHelper
{
    public static function convertImageEntityList($imageList)
    {
        $imageArray = array();
        foreach($imageList as $path=>$image)
        {
            $imageInfo = self::convertImageEntity($image);
            $imageArray[$path] = $imageInfo;
        }

        return $imageArray;
    }

    public static function convertImageEntity($image)
    {
        $imageArray = array(
            ImageResField::IMAGE_HASH => $image->getImageHash(),
        );
        return $imageArray;
    }

    public static function convertVideoEntity($video)
    {
        $videoArray = array(
            VideoResField::VIDEO_ID => $video->getVideoId(),
        );
        return $videoArray;
    }

    public static function convertVideoList($videoList)
    {
        $videoArray = array();
        foreach ($videoList as $path=>$video)
        {
            $videoInfo = self::convertVideoEntity($video);
            $videoArray[$path] = $videoInfo;
        }

        return $videoArray;
    }

    public static function checkCreativeParam($requestParam)
    {
        $accountId = CommonHelper::getArrayValueByKey(QueryParamConstant::ACCOUNT_ID, $requestParam);
        if(empty($accountId))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#checkCreativeParam# There is not param or empty: ' . QueryParamConstant::ACCOUNT_ID);
            return false;
        }

        $adsetId = CommonHelper::getArrayValueByKey(QueryParamConstant::ADSET_ID, $requestParam);
        if(empty($adsetId))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#checkCreativeParam# There is not param or empty: ' . QueryParamConstant::ADSET_ID);
            return false;
        }

        $adName = CommonHelper::getArrayValueByKey(QueryParamConstant::AD_NAME, $requestParam);
        if(empty($adName))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#checkCreativeParam# There is not param or empty: ' . QueryParamConstant::AD_NAME);
            return false;
        }

        $creativeInfo = CommonHelper::getArrayValueByKey(QueryParamConstant::CREATIVE_INFO_FIELD, $requestParam);
        if(empty($creativeInfo))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#checkCreativeParam# There is not param or empty: ' . QueryParamConstant::CREATIVE_INFO_FIELD);
            return false;
        }

        return true;
    }

    public static function buildCreativeInfo($paramCreative)
    {
        $dataType = CommonHelper::getArrayValueByKey(QueryParamConstant::CREATIVE_DATA_TYPE, $paramCreative);
        $storyData = CommonHelper::getArrayValueByKey(QueryParamConstant::CREATIVE_STORY_DATA, $paramCreative);
        $mainField = CommonHelper::getArrayValueByKey(QueryParamConstant::CREATIVE_MAIN_FIELD, $paramCreative);
        if(empty($dataType) || empty($storyData) || empty($mainField))
        {
            return false;
        }

        $storySpec = $mainField[AdCreativeFields::OBJECT_STORY_SPEC];
        if($dataType == FBCommonConstant::DATA_TYPE_SINGLE_VIDEO)
        {
            $videoDataObject = new AdCreativeVideoData();
            $videoDataObject->setData($storyData);
            $storySpec[AdCreativeObjectStorySpecFields::VIDEO_DATA] = $videoDataObject;

            $storySpecObject = new AdCreativeObjectStorySpec();
            $storySpecObject->setData($storySpec);
            $mainField[AdCreativeFields::OBJECT_STORY_SPEC] = $storySpecObject;
        }
        else
        {
            $linkDataObject = new AdCreativeLinkData();
            $linkDataObject->setData($storyData);
            $storySpec[AdCreativeObjectStorySpecFields::LINK_DATA] = $linkDataObject;

            $storySpecObject = new AdCreativeObjectStorySpec();
            $storySpecObject->setData($storySpec);
            $mainField[AdCreativeFields::OBJECT_STORY_SPEC] = $storySpecObject;
        }

        return $mainField;
    }
}