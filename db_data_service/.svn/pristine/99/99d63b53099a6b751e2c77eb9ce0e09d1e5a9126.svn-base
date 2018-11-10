<?php
namespace DBService\Business;
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Service\ServiceBase;
use DBService\Common\OrionDBServiceResult;
use DBService\Constant\OrionDBQueryParamKey;
use DBService\Constant\OrionDBStatusCode;
use DBService\Constant\ServiceAPIConstant;
use DBService\DBField\OrionDB\ProductDBFields;
use DBService\DBField\OrionDB\SelectAliasConstants;
use DBService\Handler\ResponseDataHelper;
use DBService\Manager\OrionDB\AccountDBManager;
use DBService\Manager\OrionDB\ProductDBManager;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/10
 * Time: 下午1:48
 */
class OrionBasicService extends ServiceBase
{
    private $productDb;

    private $accountDb;

    public function __construct()
    {
        $this->productDb = new ProductDBManager();
        $this->accountDb = new AccountDBManager();
    }

    public function getAccountsByProductId($requestParam)
    {
        $response = new OrionDBServiceResult();
        if(!array_key_exists(OrionDBQueryParamKey::PRODUCT_ID, $requestParam))
        {
            $response->setErrorCode(OrionDBStatusCode::PARAM_NULL_PRODUCT_ID);
            return $response;
        }

        $productId = $requestParam[OrionDBQueryParamKey::PRODUCT_ID];
        $accountList = $this->accountDb->selectAccountByProductId($productId);
        if(false === $accountList)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select account by productId: ' . $productId );
            $response->setErrorCode(OrionDBStatusCode::FAILED_DB_QUERY_ACCOUNT);
            return $response;
        }

        $accountData = ResponseDataHelper::convertAccountList($accountList);
        $response->setData($accountData);
        return $response;
    }

    public function getValidProductInfo()
    {
        $response = new OrionDBServiceResult();

        $productStateList = array();
        $productInfoList = $this->productDb->selectValidProduct();
        if(false === $productInfoList)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to query valid Product.');
            $response->setErrorCode(OrionDBStatusCode::FAILED_QUERY_PRODUCT);
            return $response;
        }
        //临时用用于过滤多平台情况，暂时只考虑FB
        $productIdList = array();
        foreach ($productInfoList as $product)
        {
            //先暂时不考虑google, 后面需要重构增加google account
            $productId = $product[ProductDBFields::ID];
            $productName = $product[SelectAliasConstants::PRODUCT_NAME];
            $logoPath = $product[ProductDBFields::LOGO_PATH];
            $advertiser = $product[SelectAliasConstants::ADVERTISER_NAME];

            if(in_array($productId, $productIdList))
            {
                continue;
            }

            $accountList = $this->accountDb->selectAccountByProductId($productId);
            if(false === $accountList)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to select account by productId: ' .
                    $productId . '; productName: ' . $productName);
                continue;
            }

            if(empty($accountList))
            {
                continue;
            }

            //没有考虑平台，目前只是FB
            $productIdList[] = $productId;

            $accountIdList = array();
            foreach ($accountList as $account)
            {
                $accountIdList[] = $account->getId();
            }

            $productState = array(
                ServiceAPIConstant::PRODUCT_ID => $productId,
                ServiceAPIConstant::PRODUCT_NAME => $productName,
                ServiceAPIConstant::PRODUCT_ADVERTISER => $advertiser,
                ServiceAPIConstant::PRODUCT_LOGO_PATH => $logoPath,
                ServiceAPIConstant::PRODUCT_ACCOUNT_LIST => $accountIdList,
            );

            $productStateList[$productId] = $productState;
        }

        $response->setData($productStateList);

        return $response;
    }
}