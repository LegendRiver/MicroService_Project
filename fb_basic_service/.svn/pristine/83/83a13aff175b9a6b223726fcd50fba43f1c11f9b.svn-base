<?php
namespace FBBasicService\Manager;

use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\AdAccountUser;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Business;

use FBBasicService\Constant\FBParamConstant;
use FBBasicService\Constant\FBParamValueConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Entity\AdAccountEntity;

use CommonMoudle\Constant\LogConstant;

class AdAccountManager
{
    private static $instance = null;

    private $defaultAccountField;

    private $campaignMgr;

    private $params;

    //测试用
    private $allAccountField;

    private function __construct()
    {
        $this->defaultAccountField = array(
            AdAccountFields::ID,
            AdAccountFields::NAME,
            //AdAccountFields::END_ADVERTISER,
            AdAccountFields::CURRENCY,
            AdAccountFields::TIMEZONE_ID,
            AdAccountFields::TIMEZONE_NAME,
            AdAccountFields::TIMEZONE_OFFSET_HOURS_UTC,
            AdAccountFields::ACCOUNT_STATUS,
            //AdAccountFields::TOS_ACCEPTED,
            //AdAccountFields::AGE,
            AdAccountFields::CREATED_TIME,
            AdAccountFields::SPEND_CAP,
            AdAccountFields::AMOUNT_SPENT,
        );
        $this->initAllFields();

        $this->campaignMgr = AdCampaignManager::instance();

        $this->params = array(
            FBParamConstant::QUERY_PARAM_LIMIT => FBParamValueConstant::QUERY_ACCOUNT_AMOUNT_LIMIT,
        );
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function getAccountByBMId($bmId)
    {
        try
        {
            $businessManager = new Business($bmId);
            $clientAccountCursor = $businessManager->getClientAdAccounts($this->defaultAccountField, $this->params);
            $clientAccountList = $this->traverseAccountCursor($clientAccountCursor);

            $ownedAccountCursor = $businessManager->getOwnedAdAccounts($this->defaultAccountField, $this->params);
            $ownedAccountList = $this->traverseAccountCursor($ownedAccountCursor);

            $accountList = array_merge($clientAccountList, $ownedAccountList);
            if(empty($accountList))
            {
                return array();
            }
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return $accountList;
    }

    public function getAccountCount($userID)
    {
        $accountArray = $this->getAllAccountByUser($userID);
        if(false === $accountArray)
        {
            return 0;
        }
        return count($accountArray);
    }


    public function getAllAccountByUser($userId)
    {
        try
        {
            $sdkUser = new AdAccountUser($userId);
            $sdkAccountCursor = $sdkUser->getAdAccounts($this->defaultAccountField, $this->params);
            $accountArray = $this->traverseAccountCursor($sdkAccountCursor);

            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, 'Read accounts of user: ' . $userId . ' succeed.');
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return $accountArray;
    }

    public function getAccountById($accountId)
    {
        $entity = null;
        try
        {
            $account = new AdAccount($accountId);
            $account->read($this->defaultAccountField);
            $entity = $this->initAccountEntity($account);
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return $entity;
    }

    private function traverseAccountCursor($accountCursor)
    {
        $accountArray = array();
        while($accountCursor->valid())
        {
            $sdkAccount = $accountCursor->current();
            $accountEntity = $this->initAccountEntity($sdkAccount);
            $accountArray[] = $accountEntity;

            $accountCursor->next();
        }

        return $accountArray;
    }

    private function initAccountEntity($sdkAccount)
    {
        $entity = new AdAccountEntity();
        $entity->setName($sdkAccount->{AdAccountFields::NAME});
        $entity->setId($sdkAccount->{AdAccountFields::ID});
        $entity->setStatus($sdkAccount->{AdAccountFields::ACCOUNT_STATUS});
        $entity->setCurrency($sdkAccount->{AdAccountFields::CURRENCY});
        $entity->setTimezoneId($sdkAccount->{AdAccountFields::TIMEZONE_ID});
        $entity->setTimezoneName($sdkAccount->{AdAccountFields::TIMEZONE_NAME});
        $entity->setTimezoneOffset($sdkAccount->{AdAccountFields::TIMEZONE_OFFSET_HOURS_UTC});
        $entity->setCreateTime($sdkAccount->{AdAccountFields::CREATED_TIME});
        $entity->setSpendCap($sdkAccount->{AdAccountFields::SPEND_CAP});
        $entity->setAmountSpend($sdkAccount->{AdAccountFields::AMOUNT_SPENT});
        return $entity;
    }

    /**
     * 获取AdAccount的所有Fields, 测试用
     */
    private function initAllFields()
    {
        $accountFields = new AdAccountFields();
        $this->allAccountField = $accountFields->getValues();
    }
}