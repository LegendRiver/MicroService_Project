<?php

namespace DBService\Business;

use CommonMoudle\Service\ServiceBase;
use DBService\Constant\OrionDBQueryParamKey;
use DBService\Constant\ServiceAPIConstant;
use DBService\Manager\OrionDB\UserDBManager;
use DBService\Common\OrionDBServiceResult;
use DBService\Constant\OrionDBStatusCode;

class UserBasicService extends ServiceBase
{
	private $userDb;


	public function __construct()
	{
		$this->userDb = new UserDBManager();
	}

	public function getUserData($param)
	{
		$response = new OrionDBServiceResult();

		if(!array_key_exists(OrionDBQueryParamKey::USER_INFO_NAME, $param))
		{
			$response->setErrorCode(OrionDBStatusCode::USER_NAME_EMPTY);
			return $response;
		}

		$name = $param[OrionDBQueryParamKey::USER_INFO_NAME];
		$db = new UserDBManager();
		$result = $db->selectUserByName($name);

		if(false === $result)
		{
			$response->setErrorCode(OrionDBStatusCode::SELECT_USER_NAME_FAIL);
			return $response;
		}
		$response->setData($result);
		return $response;
	}

	public function getUserById($param)
	{
		$response = new OrionDBServiceResult();

		if(!array_key_exists(OrionDBQueryParamKey::USER_INFO_ID, $param))
		{
			$response->setErrorCode(OrionDBStatusCode::USER_ID_EMPTY);
			return $response;
		}

		$id = $param[OrionDBQueryParamKey::USER_INFO_ID];
		$db = new UserDBManager();
		$userInfo = $db->selectUserById($id);

		if(false === $userInfo)
		{
			$response->setErrorCode(OrionDBStatusCode::SELECT_USER_NAME_FAIL);
			return $response;
		}
		$userInfos = array();
		$userInfos[0][ServiceAPIConstant::USER_INFO_NAME] = $userInfo[0][ServiceAPIConstant::USER_INFO_NAME];
		$userInfos[0][ServiceAPIConstant::USER_INFO_EMAIL] = $userInfo[0][ServiceAPIConstant::USER_INFO_EMAIL];
		$response->setData($userInfos);
		return $response;
	}
}