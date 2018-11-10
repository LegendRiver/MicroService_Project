<?php
namespace FBBasicService\Util;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\CommonHelper;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Search\TargetingSearchTypes;
use FacebookAds\Object\Fields\AdAccountTargetingUnifiedFields;
use FacebookAds\Object\Fields\TargetingGeoLocationFields;
use FacebookAds\Object\Fields\ReachEstimateFields;
use FacebookAds\Object\Fields\ReachFrequencyPredictionFields;
use FacebookAds\Object\ReachFrequencyPrediction;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBParamConstant;
use FBBasicService\Entity\ApplicationEntity;
use FBBasicService\Entity\CountrySearchEntity;
use FBBasicService\Entity\ReachEstimateEntity;
use FBBasicService\Entity\ReachFrequencyEntity;
use FBBasicService\Entity\TargetingSearchEntity;
use FBBasicService\Extend\EliAdAccount;
use FBBasicService\Extend\EliAdSet;

class TargetingSearchUtil
{
    public static function estimateReachFrequency($accountId, $reachFrequencyParam)
    {
        if(empty($reachFrequencyParam))
        {
            return false;
        }

        try
        {
            $account = new AdAccount($accountId);

            $prediction = $account->createReachFrequencyPrediction(array(), $reachFrequencyParam);

            $fields = self::getReachFrequencyFields();
            $predictionInfo = $prediction->read($fields);

            $entity = self::buildReachFrequencyEntity($predictionInfo);
            return $entity;

        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }

    public static function estimateReachBid($accountId, $estimateParam, $deliveryParam = array())
    {
        if(empty($estimateParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'The estimateParam is empty.');
            return false;
        }
        if(empty($deliveryParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The deliveryParam is empty.');
            return false;
        }

        $resultArray = array();
        try
        {
            $dataArray = self::getDeliveryEstimateByAct($accountId, $deliveryParam);
            $account = new AdAccount($accountId);
            $queryField = array(
                ReachEstimateFields::USERS,
                ReachEstimateFields::UNSUPPORTED,
                ReachEstimateFields::ESTIMATE_READY,
            );
            $estimateCursor = $account->getReachEstimate($queryField, $estimateParam);

            while($estimateCursor->valid())
            {
                $currentEstimate = $estimateCursor->current();
                $entity = self::buildReachEstimateEntity($currentEstimate, $dataArray);
                $resultArray[] = $entity;

                //$estimateCursor->next();
                break;
            }
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return $resultArray;
    }

    public static function getDeliveryEstimateByAct($accountId, $deliveryParam, $fields = array())
    {
        $eliAccount = new EliAdAccount($accountId);
        if(empty($fields))
        {
            $fields = array(
                FBParamConstant::DELIVERY_FIELD_BID,
                FBParamConstant::DELIVERY_FIELD_CURVE,
                FBParamConstant::DELIVERY_FIELD_DAU,
                FBParamConstant::DELIVERY_FIELD_MAU,
            );
        }
        $deliveryCursor = $eliAccount->getDeliveryEstimate($fields, $deliveryParam);
        while($deliveryCursor->valid())
        {
            $estimate = $deliveryCursor->current();
            $dataArray = $estimate->getData();
            return $dataArray;
        }
        return array();
    }

    public static function getDeliveryEstimateByAdSet($adsetId, $fields = array())
    {
        if(empty($fields))
        {
            $fields = array(
                FBParamConstant::DELIVERY_FIELD_BID,
                FBParamConstant::DELIVERY_FIELD_CURVE,
                FBParamConstant::DELIVERY_FIELD_DAU,
                FBParamConstant::DELIVERY_FIELD_MAU,
            );
        }
        $adset = new EliAdSet($adsetId);
        $deliveryCursor = $adset->getDeliveryEstimate($fields);
        while($deliveryCursor->valid())
        {
            $estimate = $deliveryCursor->current();
            $dataArray = $estimate->getData();
            return $dataArray;
        }
        return array();
    }

    public static function searchLocalID($languageDes)
    {
        $localeArray = array();
        $localParam = array(
            FBParamConstant::QUERY_PARAM_LIMIT => 1000,
        );
        $resultCursor = self::basicSearch(TargetingSearchTypes::LOCALE, null, $languageDes, $localParam);
        if(!isset($resultCursor))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Did not search the localeId by ' . $languageDes);
            return null;
        }
        while($resultCursor->valid())
        {
            $currentObject = $resultCursor->current();
            $currentArray = $currentObject->exportData();
            $currentKey = $currentArray[FBParamConstant::LOCALE_SEARCH_KEY];
            $currentName = $currentArray[FBParamConstant::LOCALE_SEARCH_NAME];
            $localeArray[$currentKey] = $currentName;
            $resultCursor->next();
        }

        return array_keys($localeArray);
    }

    public static function searchApplicationEntity($appURL)
    {
        $appEntity = null;
        $resultCursor = self::basicSearch(FBParamConstant::APPLICATION_SEARCH_TYPE, null,
            null, array(FBParamConstant::APPLICATION_SEARCH_PARA_URL => $appURL));
        if(!isset($resultCursor))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Did not search the applicationID by ' . $appURL);
            return null;
        }

        if ($resultCursor->count() > 1)
        {
            //打印日志，如果多个只取第一个
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'The number of search result is greater than 1 by ' . $appURL);
        }
        while($resultCursor->valid())
        {
            $appEntity = new ApplicationEntity();
            $currentObject = $resultCursor->current();
            $currentArray = $currentObject->exportData();
            $appEntity->setId($currentArray[FBParamConstant::APPLICATION_SEARCH_ID]);
            $appEntity->setName($currentArray[FBParamConstant::APPLICATION_SEARCH_NAME]);

            if(array_key_exists(FBParamConstant::APPLICATION_SEARCH_DESCRIPTION, $currentArray))
            {
                $appEntity->setDescription($currentArray[FBParamConstant::APPLICATION_SEARCH_DESCRIPTION]);
            }

            $appEntity->setImageUrl($currentArray[FBParamConstant::APPLICATION_SEARCH_PICTURE]);
            $appEntity->setOriginalUrl($currentArray[FBParamConstant::APPLICATION_SEARCH_ORIGINURL]);
            if (array_key_exists(FBParamConstant::APPLICATION_SEARCH_STOREURL, $currentArray))
            {
                $appEntity->setStoreUrl($currentArray[FBParamConstant::APPLICATION_SEARCH_STOREURL]);
            }
            else
            {
                $appEntity->setStoreUrl($currentArray[FBParamConstant::APPLICATION_SEARCH_ORIGINURL]);
            }

            if (array_key_exists(FBParamConstant::APPLICATION_SEARCH_SUPPORTDEVICE, $currentArray))
            {
                $appEntity->setSupportDeviceArray($currentArray[FBParamConstant::APPLICATION_SEARCH_SUPPORTDEVICE]);
            }
            break;
        }

        return $appEntity;
    }

    public static function queryTargetingList($searchClass)
    {
        $resultArray = array();
        $interestParam = array(
            FBParamConstant::QUERY_PARAM_LIMIT => 1000,
        );

        $resultCursor = self::basicSearch(TargetingSearchTypes::TARGETING_CATEGORY, $searchClass, null, $interestParam);
        if(!isset($resultCursor))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Failed to query Targeting list. ');
            return $resultArray;
        }
        while($resultCursor->valid())
        {
            $currentObject = $resultCursor->current();
            $currentArray = $currentObject->exportData();
            $searchEntity = self::buildTargetingSearchEntity($currentArray);
            $resultArray[] = $searchEntity;
            $resultCursor->next();
        }

        return $resultArray;

    }

    public static function searchInterestId($interestDes)
    {
        $resultArray = array();
        if(empty($interestDes))
        {
            return $resultArray;
        }

        $resultCursor = self::basicSearch(TargetingSearchTypes::INTEREST, null, $interestDes);
        if(!isset($resultCursor))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Did not search the interest by ' . $interestDes);
            return null;
        }
        while($resultCursor->valid())
        {
            $currentObject = $resultCursor->current();
            $currentArray = $currentObject->exportData();
            $searchEntity = self::buildTargetingSearchEntity($currentArray);
            $interestId = $searchEntity->getId();
            $resultArray[$interestId] = $searchEntity;
            $resultCursor->next();
        }

        return array_keys($resultArray);

    }

    public static function searchLocationCode($locationType, $locationDesc=null, $optionalParam=null)
    {
        $resultArray = array();
        //如果countryDesc 为 null 则全查
        $locationParam = array(
            TargetingGeoLocationFields::LOCATION_TYPES => array($locationType),
            FBParamConstant::QUERY_PARAM_LIMIT => 1000,
        );

        if(!empty($optionalParam))
        {
            $locationParam = array_merge($locationParam, $optionalParam);
        }

        $resultCursor = self::basicSearch(TargetingSearchTypes::GEOLOCATION, null, $locationDesc, $locationParam);

        if(!isset($resultCursor))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Did not search the country by ' . $locationDesc);
            return null;
        }
        while($resultCursor->valid())
        {
            $currentObject = $resultCursor->current();
            $currentArray = $currentObject->exportData();
            $countryEntity = self::buildCountryEntity($currentArray);
            $key = $countryEntity->getCountryCode();
            $resultArray[$key] = $countryEntity;
            $resultCursor->next();
        }

        return array_keys($resultArray);
    }

    private static function basicSearch($searchType, $searchClass=null, $searchQ=null, $searchPara=array())
    {
        $searchResult = null;
        try
        {
            $searchResult = TargetingSearch::search($searchType, $searchClass, $searchQ, $searchPara);
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
        }
        return $searchResult;
    }

    private static function buildTargetingSearchEntity(array $searchResult)
    {
        $entity = new TargetingSearchEntity();
        if(array_key_exists(AdAccountTargetingUnifiedFields::ID, $searchResult))
        {
            $entity->setId($searchResult[AdAccountTargetingUnifiedFields::ID]);
        }
        if(array_key_exists(AdAccountTargetingUnifiedFields::NAME, $searchResult))
        {
            $entity->setName($searchResult[AdAccountTargetingUnifiedFields::NAME]);
        }
        if(array_key_exists(AdAccountTargetingUnifiedFields::AUDIENCE_SIZE, $searchResult))
        {
            $entity->setAudienceSize($searchResult[AdAccountTargetingUnifiedFields::AUDIENCE_SIZE]);
        }
        if(array_key_exists(AdAccountTargetingUnifiedFields::PATH, $searchResult))
        {
            $entity->setPath($searchResult[AdAccountTargetingUnifiedFields::PATH]);
        }
        if(array_key_exists(AdAccountTargetingUnifiedFields::DESCRIPTION, $searchResult))
        {
            $entity->setDescription($searchResult[AdAccountTargetingUnifiedFields::DESCRIPTION]);
        }
        if(array_key_exists(AdAccountTargetingUnifiedFields::TYPE, $searchResult))
        {
            $entity->setType($searchResult[AdAccountTargetingUnifiedFields::TYPE]);
        }
        if(array_key_exists(FBParamConstant::TARGETING_SEARCH_PLATFORM, $searchResult))
        {
            $entity->setPlatform($searchResult[FBParamConstant::TARGETING_SEARCH_PLATFORM]);
        }

        return $entity;
    }

    private static function buildCountryEntity(array $searchResult)
    {
        $entity = new CountrySearchEntity();
        $entity->setKey($searchResult[FBParamConstant::COUNTRY_SEARCH_KEY]);
        $entity->setName($searchResult[FBParamConstant::COUNTRY_SEARCH_NAME]);
        $entity->setCountryCode($searchResult[FBParamConstant::COUNTRY_SEARCH_COUNTRY_CODE]);
        $entity->setType($searchResult[FBParamConstant::COUNTRY_SEARCH_TYPE]);

        return $entity;
    }

    private static function buildReachFrequencyEntity(ReachFrequencyPrediction $prediction)
    {
        $dataArray = $prediction->getData();
        $entity = new ReachFrequencyEntity();
        $entity->setPredictionId($dataArray[ReachFrequencyPredictionFields::ID]);
        $entity->setAccountId($dataArray[ReachFrequencyPredictionFields::ACCOUNT_ID]);
        $entity->setStartTime($dataArray[ReachFrequencyPredictionFields::CAMPAIGN_TIME_START]);
        $entity->setStopTime($dataArray[ReachFrequencyPredictionFields::CAMPAIGN_TIME_STOP]);
        $entity->setCurveBudgetReach($dataArray[ReachFrequencyPredictionFields::CURVE_BUDGET_REACH]);
        $entity->setDestinationId($dataArray[ReachFrequencyPredictionFields::DESTINATION_ID]);
        $entity->setBudget($dataArray[ReachFrequencyPredictionFields::EXTERNAL_BUDGET]);
        $entity->setReach($dataArray[ReachFrequencyPredictionFields::EXTERNAL_REACH]);
        $entity->setImpression($dataArray[ReachFrequencyPredictionFields::EXTERNAL_IMPRESSION]);
        $entity->setFrequencyCap($dataArray[ReachFrequencyPredictionFields::FREQUENCY_CAP]);
        $entity->setAudienceSize($dataArray[ReachFrequencyPredictionFields::TARGET_AUDIENCE_SIZE]);
        $entity->setIntervalFrequency($dataArray[ReachFrequencyPredictionFields::INTERVAL_FREQUENCY_CAP_RESET_PERIOD]);
        $entity->setFrequencyDistribution(CommonHelper::getArrayValueByKey(FBParamConstant::FIELD_FREQUENCY_DISTRIBUTION, $dataArray));
        return $entity;
    }


    private static function buildReachEstimateEntity($estimate, $deliveryData)
    {
        $dataArray = $estimate->exportData();
        $estimateEntity = new ReachEstimateEntity();

        $estimateEntity->setUserCount($dataArray[ReachEstimateFields::USERS]);

        $bidEstimate = $deliveryData[FBParamConstant::DELIVERY_FIELD_BID];
        if(empty($bidEstimate))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The bidData is empty');
            return $estimateEntity;
        }

        $estimateEntity->setBidMin($bidEstimate[FBParamConstant::REACH_ESTIMATE_BID_FIELD_MIN]);
        $estimateEntity->setBidMax($bidEstimate[FBParamConstant::REACH_ESTIMATE_BID_FIELD_MAX]);
        $estimateEntity->setBidMedian($bidEstimate[FBParamConstant::REACH_ESTIMATE_BID_FIELD_MEDIAN]);

        $dau = $deliveryData[FBParamConstant::DELIVERY_FIELD_DAU];
        $curve = $deliveryData[FBParamConstant::DELIVERY_FIELD_CURVE];
        if(count($curve) > 1)
        {
            $estimateEntity->setDua($dau);
            $estimateEntity->setCurve($curve);
        }

        return $estimateEntity;
    }

    private static function getReachFrequencyFields($isAll=false)
    {
        if($isAll)
        {
            $predictionFields = new ReachFrequencyPredictionFields();
            return $predictionFields->getValues();
        }
        else
        {
            return self::$reachFrequencyFields;
        }
    }

    private static $reachFrequencyFields = array(
        ReachFrequencyPredictionFields::ACCOUNT_ID,
        ReachFrequencyPredictionFields::CAMPAIGN_TIME_START,
        ReachFrequencyPredictionFields::CAMPAIGN_TIME_STOP,
        ReachFrequencyPredictionFields::CURVE_BUDGET_REACH,
        ReachFrequencyPredictionFields::DESTINATION_ID,
        ReachFrequencyPredictionFields::EXTERNAL_BUDGET,
        ReachFrequencyPredictionFields::EXTERNAL_REACH,
        ReachFrequencyPredictionFields::EXTERNAL_IMPRESSION,
        ReachFrequencyPredictionFields::FREQUENCY_CAP,
        ReachFrequencyPredictionFields::ID,
        ReachFrequencyPredictionFields::INTERVAL_FREQUENCY_CAP_RESET_PERIOD,
        ReachFrequencyPredictionFields::TARGET_AUDIENCE_SIZE,
        FBParamConstant::FIELD_FREQUENCY_DISTRIBUTION,
    );
}