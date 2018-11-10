<?php

namespace OrionService\Business;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use OrionService\Constant\CommonConstant;
use OrionService\Constant\InField\DBUserInfoField;
use OrionService\Constant\OrionServiceStateCode;
use OrionService\Constant\OutField\UserInfoField;
use OrionService\Constant\QueryParamConstant;
use CommonMoudle\Helper\JWTHelper;
use OrionService\Common\OrionServiceResult;
use OrionService\DB\DBUserServiceFacade;


class UserAuthService
{
	public function validUser($param)
	{
		$response = new OrionServiceResult();
		if (!array_key_exists(QueryParamConstant::USER_NAME, $param) || !array_key_exists(QueryParamConstant::USER_PASSWORD, $param))
		{
			$response->setErrorCode(OrionServiceStateCode::QUERY_USER_BY_NAME_PARAMETER_LOSS);
			return $response;
		}
		$paramUserName = $param[QueryParamConstant::USER_NAME];
		$paramPassword = $param[QueryParamConstant::USER_PASSWORD];
		$data = array();
		$md5Password = md5(md5($paramPassword));
		$dbData = DBUserServiceFacade::queryUserByName($paramUserName);
        if (empty($dbData))
        {
            $response->setErrorCode(OrionServiceStateCode::QUERY_USER_BY_NAME_FAILED);
            return $response;
        }

		$dbId = $dbData[DBUserInfoField::ID];
		$dbUserName = $dbData[DBUserInfoField::NAME];

		if ($paramUserName !== $dbUserName || $dbData[DBUserInfoField::PASSWORD] !== $md5Password)
		{
            $response->setErrorCode(OrionServiceStateCode::QUERY_USER_BY_NAME_PARAMETER_WRONG);
            return $response;
		}

        $payload = array(
            CommonConstant::USERNAME => $dbUserName
        );
        $token = JWTHelper::genJWT($payload);
        if(empty($token))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to generate token.');
            $response->setErrorCode(OrionServiceStateCode::QUERY_USER_FAILED_GEN_TOKEN);
            return $response;
        }

		$data[UserInfoField::ID] = $dbId;
		$data[UserInfoField::USERNAME] = $dbUserName;
		$data[UserInfoField::TOKEN] = $token;
		$response->setData($data);
		return $response;
	}
}