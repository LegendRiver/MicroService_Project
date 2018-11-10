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

class CarouselImageCopyHelper extends CarouselCopyHelper
{

    protected function getUploadMaterial($accountId, $materialPath)
    {
        $dirMaterialMap = array();
        $subDirList = FileHelper::getSubDirList($materialPath, array('__MACOSX'));
        foreach($subDirList as $dir)
        {
            $dirName = basename($dir);
            $pathMap = $this->uploadImageOperation($accountId, $materialPath);
            $dirMaterialMap[$dirName] = $pathMap;
        }

        return $dirMaterialMap;
    }

    protected function getCustomDataType()
    {
        return DACommonConstant::AD_TYPE_CAROUSEL_IMAGE;
    }

    protected function replaceOneAttachment($oldAttachInfo, $materialId, $headline)
    {
        $oldAttachInfo[FBFieldConstant::ATTACHMENT_IMAGE_HASH] = $materialId;

        if(!empty($headline))
        {
            $oldAttachInfo[FBFieldConstant::ATTACHMENT_NAME] = $headline;
        }

        return $oldAttachInfo;
    }
}