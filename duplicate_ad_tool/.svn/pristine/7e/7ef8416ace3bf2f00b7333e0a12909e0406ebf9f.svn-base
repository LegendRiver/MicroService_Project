<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/6
 * Time: 上午10:39
 */

namespace DuplicateAd\Business;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\FileHelper;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Constant\ConfConstant;
use DuplicateAd\Constant\FBFieldConstant;
use DuplicateAd\FBManager\FBServiceFacade;

abstract class CarouselCopyHelper extends AbstractCopyHelper
{
    protected $attachments;

    abstract protected function getCustomDataType();
    abstract protected function replaceOneAttachment($oldAttachInfo, $materialId, $headline);

    protected function handlerCopyAd()
    {
        foreach ($this->materialInfos as $dirName=>$materialInfo)
        {
            $newAdsetName = $this->formatAdsetName($this->temAdsetName, $dirName);

            $newAdsetId = $this->duplicateAdset($newAdsetName);

            if(false === $newAdsetId)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to duplicate adset for dir: ' . $dirName);
                $this->failedCopyNameList[] = $dirName;
                continue;
            }

            $adName = date('Ymd_') . $dirName;
            $textInfo = CommonHelper::getArrayValueByKey($dirName, $this->materialTextMap);

            $templateAttachCount = $this->getAttachmentCount();
            if(empty($templateAttachCount))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The template ad is not carousel.');
                $this->failedCopyNameList[] = $dirName;
                continue;
            }

            $materialCount = count($materialInfo);
            if($materialCount == 0)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The material is empty.');
                $this->failedCopyNameList[] = $dirName;
                continue;
            }

            $creativeInfo = $this->buildCarouselCreative($materialInfo, $textInfo, $adName);
            if(false === $creativeInfo)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to build carousel creative.');
                $this->failedCopyNameList[] = $dirName;
                continue;
            }

            $adId = FBServiceFacade::createAdByCreative($this->toAccountId, $newAdsetId, $adName, $creativeInfo);
            if(false === $adId)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to create ad by creative.');
                $this->failedCopyNameList[] = $dirName;
                continue;
            }
        }
    }

    private function buildCarouselCreative($materialInfo, $textInfo, $adName)
    {
        $customCreativeField = array();
        $customCreativeField[FBFieldConstant::CUSTOM_DATA_TYPE] = $this->getCustomDataType();

        $creativeInfo = $this->temCreativeFields;
        if(!empty($adName))
        {
            $creativeInfo[FBFieldConstant::CREATIVE_NAME] = $adName . '_creative_' . time();
        }

        $firstMaterialPath = key($materialInfo);
        $firstText = $this->getTextInfo($firstMaterialPath, $textInfo);
        if(!empty($firstText))
        {
            $message = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_MESSAGE, $firstText);
            if(!empty($message))
            {
                $creativeInfo[FBFieldConstant::CREATIVE_BODY] = $message;
            }

            $headline = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_HEADLINE, $firstText);
            if(!empty($headline))
            {
                $creativeInfo[FBFieldConstant::CREATIVE_TITLE] = $headline;
            }
        }
        $customCreativeField[FBFieldConstant::CUSTOM_MAIN_FIELD] = $creativeInfo;

        $storySpec = $creativeInfo[FBFieldConstant::CREATIVE_OBJECT_STORY_SPEC];
        if(array_key_exists(FBFieldConstant::STORY_LINK_DATA, $storySpec))
        {
            $linkData = $storySpec[FBFieldConstant::STORY_LINK_DATA];
            $replacedAttachment = $this->replaceAttachments($materialInfo, $textInfo);
            if(empty($replacedAttachment))
            {
                ServerLogger::instance()->writLog(LogConstant::LOGGER_LEVEL_ERROR, '#duplicate carousel#The replaced attachment is empty.');
                return false;
            }
            else
            {
                $linkData[FBFieldConstant::LINK_CHILD_ATTACHMENTS] = $replacedAttachment;
            }

            if(!empty($message))
            {
                $linkData[FBFieldConstant::LINK_MESSAGE] = $message;
            }

            $customCreativeField[FBFieldConstant::CUSTOM_STORY_DATA] = $linkData;
        }
        else
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#copy# There is not link data in creative.');
            return false;
        }

        return $customCreativeField;
    }

    private function replaceAttachments($materialInfo, $textInfoMap)
    {
        $attachments = $this->attachments;
        $materialCount = min(count($attachments), count($materialInfo));
        ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO,
            'attachments count: ' . count($attachments) . '; material count: ' . count($materialInfo));

        $newAttachment = array();
        for($index=0; $index < $materialCount; ++$index)
        {
            $attachInfo = $attachments[$index];
            list($path, $materialId) = each($materialInfo);
            $textInfo = $this->getTextInfo($path, $textInfoMap);
            if(empty($textInfo))
            {
                $headline = '';
            }
            else
            {
                $headline = CommonHelper::getArrayValueByKey(ConfConstant::CSV_COL_HEADLINE, $textInfo);
            }

            $newAttachment[$index] = $this->replaceOneAttachment($attachInfo, $materialId, $headline);
        }

        return $newAttachment;
    }

    private function getTextInfo($imagePath, $textInfoMap)
    {
        if(empty($textInfoMap))
        {
            return array();
        }
        $imageName = FileHelper::getFileNameFromPath($imagePath);
        if(array_key_exists($imageName, $textInfoMap))
        {
            return $textInfoMap[$imageName];
        }
        else
        {
            return array();
        }
    }

    private function getAttachmentCount()
    {
        if(!is_array($this->attachments))
        {
            return 0;
        }
        return count($this->attachments);
    }

    private function getAttachment()
    {
        $storySpec = $this->temCreativeFields[FBFieldConstant::CREATIVE_OBJECT_STORY_SPEC];
        if(array_key_exists(FBFieldConstant::STORY_LINK_DATA, $storySpec))
        {
            $linkData = $storySpec[FBFieldConstant::STORY_LINK_DATA];
            if(array_key_exists(FBFieldConstant::LINK_CHILD_ATTACHMENTS, $linkData))
            {
                return $linkData[FBFieldConstant::LINK_CHILD_ATTACHMENTS];
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

    protected function subReset()
    {
        $this->attachments = array();
    }

    protected function subInit()
    {
        $this->attachments = $this->getAttachment();
    }

    protected function constructTextMap()
    {
        $dirNameMap = array();
        foreach ($this->originalTextInfo as $info)
        {
            $dirName = $info[0];
            $imageName = $info[1];
            $row = array(
                ConfConstant::CSV_COL_DIR_NAME => $dirName,
                ConfConstant::CSV_COL_IMAGE_NAME => $imageName,
                ConfConstant::CSV_COL_MESSAGE => $info[2],
                ConfConstant::CSV_COL_HEADLINE => $info[3],
                ConfConstant::CSV_COL_DEEP_LINK => $info[4],
            );

            if(array_key_exists($dirName, $dirNameMap))
            {
                $dirNameMap[$dirName][$imageName] = $row;
            }
            else
            {
                $imageMap = array($imageName => $row);
                $dirNameMap[$dirName] = $imageMap;
            }
        }

        return $dirNameMap;
    }
}