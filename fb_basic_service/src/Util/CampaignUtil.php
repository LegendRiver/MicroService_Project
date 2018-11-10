<?php
namespace FBBasicService\Util;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Fields\AdPromotedObjectFields;
use FBBasicService\Builder\CampaignFieldBuilder;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\CampaignParamValues;
use FBBasicService\Constant\FBParamValueConstant;
use FBBasicService\Param\CampaignCreateParam;

class CampaignUtil
{
    public static function transformCampaignField(CampaignCreateParam $param)
    {
        $builder = new CampaignFieldBuilder();
        $builder->setCampaignName($param->getName());
        $builder->setSpendCap($param->getSpendCap());
        $builder->setStatus(self::getCampaignStatus($param->getStatus()));

        $objectType = self::getCampaignObjectValue($param->getCampaignType());
        if(is_null($objectType))
        {
            return false;
        }
        else
        {
            $builder->setObjectType($objectType);
        }

        $promotedObject = self::getPromotedObject($param);
        if(false === $promotedObject)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'There is not product catalog id in product sales campaign.');
            return false;
        }
        else
        {
           $builder->setPromotedObjectArray($promotedObject);
        }

        return $builder->getOutputField();

    }

    private static function getPromotedObject(CampaignCreateParam $param)
    {
        $promotedArray = array();
        $pCampaignType = $param->getCampaignType();
        $productCatalogId = $param->getProductCatalogId();

        if($pCampaignType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_PRODUCT_SALES)
        {
            if(empty($productCatalogId))
            {
                return false;
            }

            $promotedArray[AdPromotedObjectFields::PRODUCT_CATALOG_ID] = $productCatalogId;
        }

        return $promotedArray;
    }

    /**
     * BRAND_AWARENESS, CONVERSIONS, EVENT_RESPONSES, EXTERNAL,
     * LEAD_GENERATION, LINK_CLICKS, APP_INSTALLS, OFFER_CLAIMS,
     * PAGE_LIKES, POST_ENGAGEMENT, PRODUCT_CATALOG_SALES, REACH, VIDEO_VIEWS
     * @param $objectType
     * @return string
     */
    private static function getCampaignObjectValue($objectType)
    {
        $resultObjective = null;

        if($objectType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_WEBSITE)
        {
            $resultObjective = CampaignObjectiveValues::LINK_CLICKS;
        }
        else if($objectType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_APP)
        {
            $resultObjective = CampaignObjectiveValues::APP_INSTALLS;
        }
        else if($objectType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_PRODUCT_SALES)
        {
            $resultObjective = CampaignObjectiveValues::PRODUCT_CATALOG_SALES;
        }
        else if($objectType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_PROMOTE_PAGE)
        {
            $resultObjective = CampaignObjectiveValues::PAGE_LIKES;
        }
        else if($objectType == CampaignParamValues::CAMPAIGN_PARAM_TYPE_BRAND_AWARENESS)
        {
            $resultObjective = CampaignObjectiveValues::BRAND_AWARENESS;
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The CampaignType is not valid.');
        }

        return $resultObjective;
    }

    private static function getCampaignStatus($status)
    {
        $resultStatus = Campaign::STATUS_ACTIVE;
        if(CommonHelper::notSetValue($status))
        {
            return $resultStatus;
        }

        if(FBParamValueConstant::PARAM_STATUS_ACTIVE == $status)
        {
            $resultStatus = Campaign::STATUS_ACTIVE;
        }
        else if(FBParamValueConstant::PARAM_STATUS_PAUSED == $status)
        {
            $resultStatus = Campaign::STATUS_PAUSED;
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Only ACTIVE and PAUSED are valid during creation');
        }

        return $resultStatus;
    }

}