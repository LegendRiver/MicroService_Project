<?php
namespace DBService\Handler;
use DBService\Constant\ServiceAPIConstant;
use DBService\Entity\OrionDB\AccountInfoEntity;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/24
 * Time: 上午11:39
 */
class ResponseDataHelper
{
    public static function convertAccountEntity(AccountInfoEntity $accountEntity)
    {
       $convertResult = array();
       if(empty($accountEntity))
       {
           return $convertResult;
       }
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_ID] = $accountEntity->getId();
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_NAME] = $accountEntity->getName();
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_PRODUCT_ID] = $accountEntity->getProductId();
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_ACCOUNT_ID] = $accountEntity->getAccountId();
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_AGENCY] = $accountEntity->getAgency();
       $convertResult[ServiceAPIConstant::ACCOUNT_ATTRIBUTE_IS_DISPLAY] = $accountEntity->getIsDisplay();

       return $convertResult;
    }

    public static function convertAccountList($accountList)
    {
        if(empty($accountList))
        {
            return array();
        }

        $resultList = array();
        foreach ($accountList as $accountEntity)
        {
            $convertArray = self::convertAccountEntity($accountEntity);
            $resultList[] = $convertArray;
        }

        return $resultList;
    }
}