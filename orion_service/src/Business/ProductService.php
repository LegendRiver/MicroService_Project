<?php
namespace OrionService\Business;

use OrionService\Common\OrionServiceResult;
use OrionService\Constant\OrionServiceStateCode;
use OrionService\Constant\QueryParamConstant;
use OrionService\DB\DBProductServiceFacade;
use OrionService\Handler\ProductServiceHandler;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/25
 * Time: 上午10:00
 */
class ProductService
{
    public function queryValidProduct($param)
    {
        $response = new OrionServiceResult();

        $productData = DBProductServiceFacade::queryValidProduct();
        if(false === $productData)
        {
            $response->setErrorCode(OrionServiceStateCode::QUERY_PRODUCT_FAILED);
            return $response;
        }

        $response->setData($productData);
        return $response;
    }

    public function queryAccountByProductId($param)
    {
        $response = new OrionServiceResult();
        if(!array_key_exists(QueryParamConstant::PRODUCT_ID, $param))
        {
            $response->setErrorCode(OrionServiceStateCode::PARAM_PRODUCT_ID_EMPTY);
            return $response;
        }
        $productId = $param[QueryParamConstant::PRODUCT_ID];

        $accountInfo = ProductServiceHandler::getAccountData($productId);
        if(false === $accountInfo)
        {
            $response->setErrorCode(OrionServiceStateCode::QUERY_ACCOUNT_SERVICE_FAILED);
            return $response;
        }

        $response->setData($accountInfo);
        return $response;
    }

    public function queryAccountGeoInsight($param)
    {
        $response = new OrionServiceResult();
        if(!array_key_exists(QueryParamConstant::PRODUCT_ID, $param))
        {
            $response->setErrorCode(OrionServiceStateCode::PARAM_PRODUCT_ID_EMPTY);
            return $response;
        }
        if(!array_key_exists(QueryParamConstant::START_DATE, $param))
        {
            $response->setErrorCode(OrionServiceStateCode::PARAM_START_DATE_EMPTY);
            return $response;
        }
        if(!array_key_exists(QueryParamConstant::END_DATE, $param))
        {
            $response->setErrorCode(OrionServiceStateCode::PARAM_END_DATE_EMPTY);
            return $response;
        }


        $productId = $param[QueryParamConstant::PRODUCT_ID];
        $startDate = $param[QueryParamConstant::START_DATE];
        $endDate = $param[QueryParamConstant::END_DATE];

        $insightInfo = ProductServiceHandler::queryAccountGEOInsight($productId, $startDate, $endDate);
        if(false === $insightInfo)
        {
            $response->setErrorCode(OrionServiceStateCode::QUERY_ACCOUNT_GEO_FAILED);
            return $response;
        }

        $response->setData($insightInfo);
        return $response;
    }
}