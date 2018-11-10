<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/7
 * Time: 下午5:11
 */

namespace DuplicateAd\Business;


use CommonMoudle\Helper\FileHelper;
use DuplicateAd\Constant\DACommonConstant;
use DuplicateAd\Constant\FBFieldConstant;
use DuplicateAd\FBManager\FBServiceFacade;

class CarouselVideoCopyHelper extends CarouselCopyHelper
{

    protected function getUploadMaterial($accountId, $materialPath)
    {
        $dirMaterialMap = array();
        $subDirList = FileHelper::getSubDirList($materialPath, array('__MACOSX'));
        foreach($subDirList as $dir)
        {
            $dirName = basename($dir);
            $pathMap = $this->uploadVideoOperation($accountId, $dir);
            $dirMaterialMap[$dirName] = $pathMap;
        }

        return $dirMaterialMap;
    }

    protected function getCustomDataType()
    {
        return DACommonConstant::AD_TYPE_CAROUSEL_VIDEO;
    }

    protected function replaceOneAttachment($oldAttachInfo, $materialId, $headline)
    {
        if(array_key_exists(FBFieldConstant::ATTACHMENT_IMAGE_HASH, $oldAttachInfo))
        {
            unset($oldAttachInfo[FBFieldConstant::ATTACHMENT_IMAGE_HASH]);
        }
        $oldAttachInfo[FBFieldConstant::ATTACHMENT_VIDEO_ID] = $materialId;

        if(!empty($headline))
        {
            $oldAttachInfo[FBFieldConstant::ATTACHMENT_NAME] = $headline;
        }

        $thumbnailUrl = FBServiceFacade::getThumbUrlOfVideo($materialId);
        if(!empty($thumbnailUrl))
        {
            $oldAttachInfo[FBFieldConstant::ATTACHMENT_PICTURE] = $thumbnailUrl;
        }

        return $oldAttachInfo;
    }
}