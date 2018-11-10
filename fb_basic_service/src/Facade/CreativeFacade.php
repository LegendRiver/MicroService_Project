<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 下午1:03
 */

namespace FBBasicService\Facade;


use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Manager\AdCreativeManager;
use FBBasicService\Manager\AdImageManager;
use FBBasicService\Manager\AdVideoManager;

class CreativeFacade
{
    public static function createImage($imagePath, $accountId)
    {
        $imageEntity = AdImageManager::instance()->createImage($imagePath, $accountId);
        if(false === $imageEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to Create Image.' . $imagePath);
            return false;
        }

        return $imageEntity;
    }

    public static function createAdVideoByField($accountId, $videoField)
    {
        $videoEntity = AdVideoManager::instance()->createVideo($accountId, $videoField);
        if(false === $videoEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to Create video.' . print_r($videoField, true));
            return false;
        }

        return $videoEntity;
    }

    public static function getVedioById($videoId)
    {
        $videoEntity = AdVideoManager::instance()->getVideoById($videoId);
        if(false === $videoEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get video by ' . $videoId);
            return false;
        }

        return $videoEntity;
    }

    public static function createCreativeByField($accountId, $creativeField)
    {
        $creativeEntity = AdCreativeManager::instance()->createCreative($accountId, $creativeField);
        if(false === $creativeEntity || !isset($creativeEntity))
        {
            //有一种异常不返回也创建成功，后面在解决这个问题;估计是手动删除的原因
            //还有超时的异常要做处理
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The return value of creative create is null : ' .
                gettype($creativeEntity));

            return false;
        }

        return $creativeEntity;
    }

    public static function getCreativeByAdId($adId)
    {
        $adEntity = self::getAdById($adId);
        if(false === $adEntity)
        {
            return false;
        }

        $creativeId = $adEntity->getCreativeId();
        $creativeEntity = AdCreativeManager::instance()->getCreativeById($creativeId);
        if (false === $creativeEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get creative by id: ' . $creativeId);
            return false;
        }

        return $creativeEntity;
    }

    public static function getAllFieldCreativeById($creativeId)
    {
        $creativeArray = AdCreativeManager::instance()->getAllFieldCreative($creativeId);
        if (false === $creativeArray)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get all field creative by id: ' . $creativeId);
            return false;
        }

        return $creativeArray;
    }
}