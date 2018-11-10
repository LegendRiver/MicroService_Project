<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/6
 * Time: 上午10:38
 */

namespace DuplicateAd\Business;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\FileHelper;
use CommonMoudle\Logger\ServerLogger;
use DuplicateAd\Constant\ConfConstant;
use DuplicateAd\Constant\FBFieldConstant;
use DuplicateAd\FBManager\FBServiceFacade;

abstract class SingleCopyHelper extends AbstractCopyHelper
{
    abstract protected function buildCreative($materialId, $adName, $textInfo);

    protected function handlerCopyAd()
    {
        foreach ($this->materialInfos as $filePath=>$materialId)
        {
            $imageName = FileHelper::getFileNameFromPath($filePath);
            $newAdsetName = $this->formatAdsetName($this->temAdsetName, $imageName);

            $newAdsetId = $this->duplicateAdset($newAdsetName);

            if(false === $newAdsetId)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to duplicate adset for image: ' . $filePath);
                $this->failedCopyNameList[] = $imageName;
                continue;
            }

            $adName = date('Ymd_') . $imageName;

            $textInfo = CommonHelper::getArrayValueByKey($imageName, $this->materialTextMap);

            $creativeFields = $this->buildCreative($materialId, $adName, $textInfo);
            if(empty($creativeFields))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to build creative.');
                $this->failedCopyNameList[] = $imageName;
                continue;
            }

            $adId = FBServiceFacade::createAdByCreative($this->toAccountId, $newAdsetId, $adName, $creativeFields);
            if(false === $adId)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to create ad by creative');
                $this->failedCopyNameList[] = $imageName;
                continue;
            }
        }
    }

    protected function setDeepLink($callToAction, $deepLink)
    {
        if(empty($callToAction))
        {
            return array();
        }

        if(!array_key_exists(FBFieldConstant::LINK_CALL_TO_ACTION_VALUE, $callToAction))
        {
            return $callToAction;
        }

        $callValue = $callToAction[FBFieldConstant::LINK_CALL_TO_ACTION_VALUE];
        if(empty($deepLink))
        {
            if(array_key_exists(FBFieldConstant::LINK_CALL_TO_ACTION_VALUE_APP_LINK, $callValue))
            {
                unset($callValue[FBFieldConstant::LINK_CALL_TO_ACTION_VALUE_APP_LINK]);
            }
        }
        else
        {
            $callValue[FBFieldConstant::LINK_CALL_TO_ACTION_VALUE_APP_LINK] = $deepLink;
        }

        $callToAction[FBFieldConstant::LINK_CALL_TO_ACTION_VALUE] = $callValue;
        return $callToAction;
    }

    protected function constructTextMap()
    {
        $imageMap = array();
        foreach ($this->originalTextInfo as $info)
        {
            $imageName = $info[1];
            $row = array(
                ConfConstant::CSV_COL_IMAGE_NAME => $imageName,
                ConfConstant::CSV_COL_MESSAGE => $info[2],
                ConfConstant::CSV_COL_HEADLINE => $info[3],
            );

            if(count($info)>=5)
            {
                $row[ConfConstant::CSV_COL_DEEP_LINK] = $info[4];
            }

            $imageMap[$imageName] = $row;
        }

        return $imageMap;
    }

}