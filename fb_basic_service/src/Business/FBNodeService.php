<?php
namespace FBBasicService\Business;
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use CommonMoudle\Helper\JsonFileHelper;
use CommonMoudle\Service\ServiceBase;
use FacebookAds\Object\Values\AdStatusValues;
use FBBasicService\Builder\AdFieldBuilder;
use FBBasicService\Business\Node\NodeHelper;
use FBBasicService\Common\FBLogger;
use FBBasicService\Common\FBServiceResult;
use FBBasicService\Constant\FBCommonConstant;
use FBBasicService\Constant\FBServiceStatusCode;
use FBBasicService\Constant\ServiceConstant\QueryParamConstant;
use FBBasicService\Constant\ServiceConstant\ResponseDataField;
use FBBasicService\Facade\AccountFacade;
use FBBasicService\Facade\AdFacade;
use FBBasicService\Facade\AdsetFacade;
use FBBasicService\Facade\CreativeFacade;
use FBBasicService\Business\Node\UploadMaterialManager;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/2
 * Time: 下午8:59
 */
class FBNodeService extends ServiceBase
{
    public function queryAccountById($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ACCOUNT_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#queryAccount# There is not param: accountId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }
        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $accountEntity = AccountFacade::getAccountById($accountId);
        if(false === $accountEntity)
        {
            $response->setErrorCode(FBServiceStatusCode::GET_FB_ACCOUNT_FAILED);
            return $response;
        }

        $accountInfo = array();
        $accountInfo[ResponseDataField::ACCOUNT_NAME] = $accountEntity->getName();
        $accountInfo[ResponseDataField::SPEND_CAP] = $accountEntity->getSpendCap();
        $accountInfo[ResponseDataField::SPEND_AMOUNT] = $accountEntity->getAmountSpend();

        $response->setData($accountInfo);
        return $response;
    }

    public function getAdType($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::AD_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getAdType# There is not param: adId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $adId = $requestParam[QueryParamConstant::AD_ID];
        $adType = AdFacade::getAdType($adId);
        if(false === $adType)
        {
            $response->setErrorCode(FBServiceStatusCode::FAILED_AD_TYPE);
            return $response;
        }

        $adTypeInfo = array();
        $adTypeInfo[ResponseDataField::AD_TYPE] = $adType;

        $response->setData($adTypeInfo);
        return $response;
    }

    public function getFirstAdId($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ADSET_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getFirstAdId# There is not param: adsetId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $adsetId = $requestParam[QueryParamConstant::ADSET_ID];
        $adList = AdFacade::getAdIdsByParentId($adsetId, FBCommonConstant::INSIGHT_EXPORT_TYPE_ADSET);
        if(false === $adList)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get ads by adsetId: ' . $adsetId);
            $response->setErrorCode(FBServiceStatusCode::FAILED_AD_BY_ADSET);
            return $response;
        }

        if(empty($adList))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The result ads is empty by adsetId: ' . $adsetId);
            $response->setErrorCode(FBServiceStatusCode::EMPTY_AD_BY_ADSET);
            return $response;
        }

        $firstAdId = $adList[0];

        $idInfo = array(ResponseDataField::FIRST_AD_ID => $firstAdId);
        $response->setData($idInfo);

        return $response;
    }

    public static function getAdsetAllField($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ADSET_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getAdsetAllField# There is not param: adsetId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $adsetId = $requestParam[QueryParamConstant::ADSET_ID];
        $adsetFields = AdsetFacade::getAllFieldAdsetById($adsetId);
        if(false === $adsetFields)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get all fields of adset: ' . $adsetId);
            $response->setErrorCode(FBServiceStatusCode::FAILED_ADSET_FIELDS);
            return $response;
        }

        $fieldInfo = array(ResponseDataField::ADSET_ALL_FIELD => $adsetFields);
        $response->setData($fieldInfo);

        return $response;
    }

    public static function getCreativeAllFields($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::AD_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#getCreativeAllFields# There is not param: adId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $adId = $requestParam[QueryParamConstant::AD_ID];
        $adEntity = AdFacade::getAdById($adId);
        if(false === $adEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get ad by id: ' . $adId);
            $response->setErrorCode(FBServiceStatusCode::FAILED_QUERY_AD);
            return $response;
        }

        $creativeId = $adEntity->getCreativeId();
        $creativeFields = CreativeFacade::getAllFieldCreativeById($creativeId);
        if(false === $creativeFields)
        {
            $response->setErrorCode(FBServiceStatusCode::FAILED_CREATIVE_FIELDS);
            return $response;
        }

        $fieldInfo = array(ResponseDataField::CREATIVE_ALL_FIELD => $creativeFields);
        $response->setData($fieldInfo);

        return $response;
    }

    public function uploadVideo($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ACCOUNT_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadVideo# There is not param: accountId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }
        if(!array_key_exists(QueryParamConstant::MATERIAL_PATH, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadVideo# There is not param: materialPath');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $materialPath = $requestParam[QueryParamConstant::MATERIAL_PATH];

        $uploadManager = new UploadMaterialManager();
        $uploadManager->uploadVideos($accountId, $materialPath);
        $successVideos = $uploadManager->getVideos();
        $failedVideos = $uploadManager->getFailedVideos();

        $videoArray = NodeHelper::convertVideoList($successVideos);
        $videoData = array(
            ResponseDataField::SUCCESS_VIDEO => $videoArray,
            ResponseDataField::FAILED_VIDEO => $failedVideos
        );

        $response->setData($videoData);
        return $response;
    }

    public function uploadImage($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ACCOUNT_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadImage# There is not param: accountId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }
        if(!array_key_exists(QueryParamConstant::MATERIAL_PATH, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#uploadImage# There is not param: materialPath');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $materialPath = $requestParam[QueryParamConstant::MATERIAL_PATH];

        $uploadManager = new UploadMaterialManager();
        $uploadManager->uploadImages($accountId, $materialPath);
        $successImages = $uploadManager->getImages();
        $failedImages = $uploadManager->getFailedImages();

        $imageArray = NodeHelper::convertImageEntityList($successImages);
        $imageData = array(
            ResponseDataField::SUCCESS_IMAGE => $imageArray,
            ResponseDataField::FAILED_IMAGE => $failedImages
        );

        $response->setData($imageData);
        return $response;
    }

    public function createAdsetByField($requestParam)
    {
        $response = new FBServiceResult();
        if(!array_key_exists(QueryParamConstant::ACCOUNT_ID, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdsetByField# There is not param: accountId');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }
        if(!array_key_exists(QueryParamConstant::ADSET_FIELDS, $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdsetByField# There is not param: adsetFields');
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $adsetField = $requestParam[QueryParamConstant::ADSET_FIELDS];
        if(empty($accountId) || empty($adsetField))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdsetByField#The params is empty');
            $response->setErrorCode(FBServiceStatusCode::EMPTY_REQUEST_PARAM);
            return $response;
        }

        $adsetFieldArray = JsonFileHelper::decodeJsonString($adsetField);
        $adsetEntity = AdsetFacade::createAdsetByFields($accountId, $adsetFieldArray);
        if(false === $adsetEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdsetByField#Failed to create adset');
            $response->setErrorCode(FBServiceStatusCode::FAILED_CREATE_ADSET);
            return $response;
        }

        $adsetId = $adsetEntity->getId();
        $resultInfo = array(ResponseDataField::ADSET_ID => $adsetId);
        $response->setData($resultInfo);
        return $response;
    }

    public function duplicateAdset($requestParam)
    {
        $response = new FBServiceResult();
        $baseAdsetId = CommonHelper::getArrayValueByKey(QueryParamConstant::BASE_ADSET_ID, $requestParam);
        if(empty($baseAdsetId))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#duplicateAdset# There is not param or empty: ' . QueryParamConstant::BASE_ADSET_ID);
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $newAdsetName = CommonHelper::getArrayValueByKey(QueryParamConstant::NEW_ADSET_NAME, $requestParam);
        $toCampaignId = CommonHelper::getArrayValueByKey(QueryParamConstant::TO_CAMPAIGN_ID, $requestParam);

        $copyParam = array();
        if(!empty($toCampaignId))
        {
            $copyParam['campaign_id']= $toCampaignId;
        }
        $adsetInfo = AdsetFacade::copyAdset($baseAdsetId, $copyParam);
        $copiedAdsetId = CommonHelper::getArrayValueByKey(FBCommonConstant::COPY_ADSET_ID, $adsetInfo);
        if(empty($copiedAdsetId))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to copy adset by id: ' . $baseAdsetId);
            $response->setErrorCode(FBServiceStatusCode::FAILED_COPY_ADSET);
            return $response;
        }

        if(!empty($newAdsetName))
        {
            $updateResult = AdsetFacade::updateAdsetName($copiedAdsetId, $newAdsetName);
            if(false === $updateResult)
            {
                FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to update adset new of adset: ' . $copiedAdsetId);
                $response->setErrorCode(FBServiceStatusCode::FAILED_UPDATE_ADSET_NAME);
                return $response;
            }
        }

        $resultInfo = array(ResponseDataField::ADSET_ID => $copiedAdsetId);
        $response->setData($resultInfo);
        return $response;
    }

    public function queryVideoFirstThumb($requestParam)
    {
        $response = new FBServiceResult();
        $videoId = CommonHelper::getArrayValueByKey(QueryParamConstant::VIDEO_ID, $requestParam);
        if(empty($videoId))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR,
                '#queryVideoFirstThumb# There is not param or empty: ' . QueryParamConstant::VIDEO_ID);
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $videoEntity = CreativeFacade::getVedioById($videoId);
        if(false === $videoEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get video by id: ' . $videoId);
            $response->setErrorCode(FBServiceStatusCode::FAILED_QUERY_VIDEO);
            return $response;
        }

        $thumbnails = $videoEntity->getThumbNails();
        $firstThumb = '';
        $imageList = CommonHelper::getArrayValueByKey(FBCommonConstant::VIDEO_THUMB_DATA, $thumbnails);

        if(!empty($imageList))
        {
            $nailArray = $imageList[0];
            $firstThumb =  CommonHelper::getArrayValueByKey(FBCommonConstant::VIDEO_THUMB_URL, $nailArray);
        }

        $thumbUrlInfo = array(ResponseDataField::THUMB_URL => $firstThumb);
        $response->setData($thumbUrlInfo);
        return $response;
    }

    public function createAdByCreativeField($requestParam)
    {
        $response = new FBServiceResult();
        if(false === NodeHelper::checkCreativeParam($requestParam))
        {
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }

        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $creativeInfo = $requestParam[QueryParamConstant::CREATIVE_INFO_FIELD];
        $adName = $requestParam[QueryParamConstant::AD_NAME];
        $adsetId = $requestParam[QueryParamConstant::ADSET_ID];

        $creativeFieldArray = JsonFileHelper::decodeJsonString($creativeInfo);
        $fbCreativeField = NodeHelper::buildCreativeInfo($creativeFieldArray);
        if(false === $fbCreativeField)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The creative is invalid');
            $response->setErrorCode(FBServiceStatusCode::INVALID_CREATIVE);
            return $response;
        }

        $creativeEntity = CreativeFacade::createCreativeByField($accountId, $fbCreativeField);
        if(false === $creativeEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdByCreativeField#Failed to create creative');
            $response->setErrorCode(FBServiceStatusCode::FAILED_CREATE_CREATIVE);
            return $response;
        }

        $creativeId = $creativeEntity->getId();
        $adBuilder = new AdFieldBuilder();
        $adBuilder->setAdsetId($adsetId);
        $adBuilder->setCreativeId($creativeId);
        $adBuilder->setName($adName);
        $adBuilder->setStatus(AdStatusValues::ACTIVE);

        $adInfo = array(ResponseDataField::CREATIVE_ID => $creativeId);

        $adEntity =  AdFacade::createAdByField($accountId, $adBuilder->getOutputField());
        if(false === $adEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#createAdByCreativeField#Failed to create ad.');
            $response->setErrorCode(FBServiceStatusCode::FAILED_CREATE_AD);
            $response->setData($adInfo);
            return $response;
        }

        $adId = $adEntity->getId();
        $adInfo[ResponseDataField::AD_ID] = $adId;
        $response->setData($adInfo);
        return $response;
    }

}