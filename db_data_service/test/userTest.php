<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use DBService\Manager\OrionDB\UserDBManager;


UserBasicService();

function userQueryTest()
{
	$db = new UserDBManager();
	$result = $db->selectAllUser();
	print_r($result);
}

function UserBasicService($param)
{
	$response = new OrionDBServiceResult();

	if(!array_key_exists('userName', $param))
	{
		$response->setErrorCode(OrionDBStatusCode::USER_NAME_EMPTY);
		return $response;
	}

	$name = $param['userName'];
	$db = new UserDBManager();
	$result = $db->selectUserByName($name);
	if(false === $result)
	{
		$response->setErrorCode(OrionDBStatusCode::SELECT_USER_NAME_FAIL);
		return $response;
	}
	print_r($result);

}