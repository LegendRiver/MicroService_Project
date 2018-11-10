<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/25
 * Time: 下午5:18
 */

namespace FBBasicService\Facade;

use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Manager\AdAccountManager;
use FBBasicService\Manager\AdUserManager;

class AccountFacade
{
    public static function getAllAccountsByBMId($bmId)
    {
        $accountEntities = AdAccountManager::instance()->getAccountByBMId($bmId);
        if(false === $accountEntities)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get accounts by bm id ' . $bmId);
            return false;
        }
        return $accountEntities;
    }

    public static function getCurrentUserAllAccounts()
    {
        $userEntity = AdUserManager::instance()->getUserInfo();
        $accountEntities = AdAccountManager::instance()->getAllAccountByUser($userEntity->getUserID());
        if(false === $accountEntities)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to get accounts by user id ' . $userEntity->getUserID());
            return false;
        }
        return $accountEntities;
    }

    public static function getAccountById($accountId)
    {
        $accountEntity = AdAccountManager::instance()->getAccountById($accountId);
        if(false === $accountEntity)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to get account by accountId : ' . $accountId);
            return false;
        }

        return $accountEntity;
    }
}