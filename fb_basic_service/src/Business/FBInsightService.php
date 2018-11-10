<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/4
 * Time: 下午4:10
 */

namespace FBBasicService\Business;


use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Service\ServiceBase;
use FBBasicService\Business\Insight\CountryBDInsightExporter;
use FBBasicService\Common\FBLogger;
use FBBasicService\Common\FBServiceResult;
use FBBasicService\Constant\FBCommonConstant;
use FBBasicService\Constant\FBServiceStatusCode;
use FBBasicService\Constant\ServiceConstant\QueryParamConstant;
use FBBasicService\Constant\ServiceConstant\ResponseDataField;

class FBInsightService extends ServiceBase
{
    public function queryAccountCountryBDInsight($requestParam)
    {
        $response = new FBServiceResult();
        if(false === $this->checkParam($requestParam))
        {
            $response->setErrorCode(FBServiceStatusCode::QUERY_PARAM_INCOMPLETE);
            return $response;
        }
        $accountId = $requestParam[QueryParamConstant::ACCOUNT_ID];
        $startDate = $requestParam[QueryParamConstant::START_DATE];
        $endDate = $requestParam[QueryParamConstant::END_DATE];

        $countryBDExporter = new CountryBDInsightExporter();
        $insightValue = $countryBDExporter->getInsightByBD($accountId,
            FBCommonConstant::INSIGHT_EXPORT_TYPE_ACCOUNT, $startDate, $endDate);

        if(empty($insightValue))
        {
            $response->setErrorCode(FBServiceStatusCode::QUERY_INSIGHT_EMPTY);
            return $response;
        }

        $insightData = array(
            ResponseDataField::INSIGHT_DATA => $insightValue,
        );
        $response->setData($insightData);

        return $response;
    }

    private function checkParam($requestParam)
    {
        if(!array_key_exists('accountId', $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#accountGEOInsight#There is not param: accountId');
            return false;
        }

        if(!array_key_exists('startDate', $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#accountGEOInsight#There is not param: startDate');
            return false;
        }

        if(!array_key_exists('endDate', $requestParam))
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, '#accountGEOInsight#There is not param: endDate');
            return false;
        }

        return true;
    }
}