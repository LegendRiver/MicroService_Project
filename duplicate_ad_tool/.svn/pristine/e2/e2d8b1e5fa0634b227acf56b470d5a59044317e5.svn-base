<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/6
 * Time: 下午2:49
 */

namespace DuplicateAd\Business;


use DuplicateAd\Constant\DACommonConstant;

class CopyHelperFactory
{
    private $adType2Helper;

    private $textInfo;

    public function __construct($textInfo)
    {
        $this->adType2Helper = array();
        $this->textInfo = $textInfo;
    }

    public function getHelperByAdType($adType)
    {
        if(array_key_exists($adType, $this->adType2Helper))
        {
            return $this->adType2Helper[$adType];
        }
        else
        {
            $copyHelper = $this->createHelper($adType);
            if(is_null($copyHelper))
            {
                return null;
            }

            $this->adType2Helper[$adType] = $copyHelper;
            return $copyHelper;
        }
    }

    private function createHelper($adType)
    {
        $copyHelper = null;
        switch ($adType)
        {
            case DACommonConstant::AD_TYPE_SINGLE_IMAGE:
                $copyHelper = new SingleImageCopyHelper($this->textInfo);
                break;
            case DACommonConstant::AD_TYPE_SINGLE_VIDEO:
                $copyHelper = new SingleVideoCopyHelper($this->textInfo);
                break;
            case DACommonConstant::AD_TYPE_CAROUSEL_IMAGE:
                $copyHelper = new CarouselImageCopyHelper($this->textInfo);
                break;
            case DACommonConstant::AD_TYPE_CAROUSEL_VIDEO:
                $copyHelper = new CarouselVideoCopyHelper($this->textInfo);
                break;
            default:
                break;
        }

        return $copyHelper;
    }
}