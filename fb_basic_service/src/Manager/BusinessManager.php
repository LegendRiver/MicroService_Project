<?php
namespace FBBasicService\Manager;

use CommonMoudle\Constant\LogConstant;
use \FacebookAds\Object\Business;
use \FacebookAds\Object\Values\BusinessRoles;
use \FacebookAds\Object\AbstractCrudObject;
use FBBasicService\Common\FBLogger;
use FBBasicService\Util\APIRequestUtil;

class BusinessManager
{
    private static $instance = null;

    private function __construct()
    {

    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

   //此接口不能使用，token没有MANAGE_EMPLOYEE_LIST权限
    public function inviteAdminPeople($bmId, $email)
    {
        try
        {
            $businessManager = new Business($bmId);
            $param = array(
                'email' => $email,
                'role' => BusinessRoles::ADMIN,
            );
            $businessManager->createUserPermission(array(), $param);
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return true;
    }

    public function getBusinessUserList($bmId)
    {
        $resultCursor = APIRequestUtil::getBMUserList($bmId);
        if(false === $resultCursor)
        {
            return false;
        }

        $userList = array();

        while($resultCursor->valid())
        {
            $currentUser = $resultCursor->current();
            $userInfo = $this->buildUserArray($currentUser);
            $userList[] = $userInfo;

            $resultCursor->next();
        }

        return $userList;
    }

    private function buildUserArray(AbstractCrudObject $response)
    {
        $responseData = $response->getData();
        if(array_key_exists('business_persona', $responseData))
        {
            return $responseData['business_persona'];
        }
        else
        {
            return $responseData['user'];
        }
    }
}