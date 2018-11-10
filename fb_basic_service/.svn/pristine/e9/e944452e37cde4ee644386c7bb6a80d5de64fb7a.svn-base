<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use FBBasicService\Facade\AdFacade;
use FBBasicService\Common\APIInit;
use FBBasicService\Facade\AdsetFacade;
use FBBasicService\Business\Insight\CountryBDInsightExporter;
use FBBasicService\Constant\FBCommonConstant;

APIInit::init();

getBDInsightTest();

function getAdTypeTest()
{
//    $adId = '23842733040020762';
//    $adId = '23842751188900070';
//    $adId = '23842677883720758';
    $adId = '23842744513020008';
    $adType = AdFacade::getAdType($adId);

    print_r($adType);
}

function adsetFacadeTest()
{
    $accountId = '';
    $adsetField = array();
    $adsetEntity = AdsetFacade::createAdsetByFields($accountId, $adsetField);
}

function getBDInsightTest()
{
    $countryBDExporter = new CountryBDInsightExporter();
    $accountId = "act_793769357471558";
    $startDate = "2018-03-17";
    $endDate = "2018-03-17";
    $insightValue = $countryBDExporter->getInsightByBD($accountId,
        FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT, $startDate, $endDate);
    print_r($insightValue);
}