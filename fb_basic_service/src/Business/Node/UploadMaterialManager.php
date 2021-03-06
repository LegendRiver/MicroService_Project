<?php
namespace FBBasicService\Business\Node;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\FileHelper;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\CreativeParamValues;
use FBBasicService\Facade\CreativeFacade;
use FBBasicService\Param\AdVideoParam;
use FBBasicService\Util\CreativeUtil;

class UploadMaterialManager
{
    private $images;
    private $videos;
    private $failedImages;
    private $failedVideos;

    public function __construct()
    {
        $this->images = array();
        $this->videos = array();
        $this->failedImages = array();
        $this->failedVideos = array();
    }

    public function getImages()
    {
        return $this->images;
    }

    public function getVideos()
    {
        return $this->videos;
    }

    public function getFailedImages()
    {
        return $this->failedImages;
    }

    public function getFailedVideos()
    {
        return $this->failedVideos;
    }

    public function uploadVideos($accountId, $videoRootPath, $extend = array('.mp4', '.gif'), $filterDir=array('__MACOSX'))
    {
        $this->videos = array();
        if(empty($videoRootPath))
        {
            return;
        }

        $videoFileList = FileHelper::getRecursiveFileList($videoRootPath, $extend, $filterDir);
        $tryTimes = 1;

        while($tryTimes > 0)
        {
            $failedList = array();
            foreach($videoFileList as $videoFile)
            {
                $videoParam = new AdVideoParam();
                $videoParam->setVideoType(CreativeParamValues::VIDEO_TYPE_COMMON);
                $videoParam->setSource($videoFile);
                $videoParam->setAccountId($accountId);
                $videoField = CreativeUtil::transformVideoField($videoParam);
                $videoEntity = CreativeFacade::createAdVideoByField($accountId, $videoField);

                if(false === $videoEntity)
                {
                    FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to upload video: ' . $videoFile);
                    $failedList[] = $videoFile;
                }
                else
                {
                    $this->videos[$videoFile] = $videoEntity;
                }
            }

            if(count($failedList) == 0)
            {
                break;
            }
            else
            {
                --$tryTimes;
                $videoFileList = $failedList;
                $this->failedVideos = $failedList;
            }
        }
    }

    public function uploadImages($accountId, $imageRootPath, $extend = array('.jpg', '.png',), $filterDir=array('__MACOSX'))
    {
        $this->images = array();
        if(empty($imageRootPath))
        {
            return;
        }
        $imageFileList = FileHelper::getRecursiveFileList($imageRootPath, $extend, $filterDir);

        $failedList = array();
        foreach($imageFileList as $imageFile)
        {
            $imageEntity = CreativeFacade::createImage($imageFile, $accountId);

            if(false === $imageEntity)
            {
                FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to upload image: ' . $imageFile);
                $failedList[] = $imageFile;
            }
            else
            {
                $this->images[$imageFile] = $imageEntity;
            }
        }

        $this->failedImages = $failedList;
    }
}