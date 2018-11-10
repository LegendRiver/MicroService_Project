<?php
namespace FBBasicService\Manager;

use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBParamConstant;
use FBBasicService\Entity\AdUserEntity;
use FacebookAds\Object\Fields\AdAccountUserFields;
use FacebookAds\Object\AdAccountUser;

class AdUserManager
{
    private static $instance = null;

    private $currentUserID;

    private $userID2UserEntity = array();


    private function __construct()
    {
        //获取me用户
        $defaultUser = $this->getDefaultUserInfo();
        $defaultUserID = $defaultUser->getUserID();
        $this->currentUserID = $defaultUserID;
        $this->userID2UserEntity[$defaultUserID] = $defaultUser;
    }

    public function getUserInfo()
    {
        return $this->userID2UserEntity[$this->currentUserID];
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function getDefaultUserInfo()
    {
        $userEntity = new AdUserEntity();
        try
        {
            $userFieldArray = array(
                AdAccountUserFields::ID,
                AdAccountUserFields::NAME
            );
            $user = new AdAccountUser(FBParamConstant::AD_USER_DES_DEFAULT);
            $user->read($userFieldArray);
            $userEntity->setUserID($user->{AdAccountUserFields::ID});
            $userEntity->setUserName($user->{AdAccountUserFields::NAME});
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
        }
        return $userEntity;
    }

}