<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
use CommonMoudle\Http\HttpClient;
use OrionService\DB\DBDuplicateTaskServiceFacade;
use OrionService\DB\DBProductServiceFacade;
use CommonMoudle\Service\ServiceResult;
use CommonMoudle\Helper\JWTHelper;
use OrionService\FB\FBServiceFacade;
initHttpServer();


generateMd5Pw();

function insertTask($taskName, $userId, $rootPath, $jsonName)
{
	$response = DBDuplicateTaskServiceFacade::duplicateTaskInsert($taskName, $userId, $rootPath, $jsonName);
}

function initHttpServer()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $serverConfFile = $confDir . DIRECTORY_SEPARATOR . 'dependence_service_conf.json';
    HttpClient::instance()->initServerInfo($serverConfFile);
}

function productDbTest()
{
    $response = DBProductServiceFacade::queryValidProduct();
}

function fbAccountTest()
{
    $response = FBServiceFacade::queryAccountById('test');
}

function adTypeTest()
{
    $adsetId = '23842671908120758';
    $adType = FBServiceFacade::getAdType($adsetId);
    print_r($adType);
}

function queryUserByName($userName)
{
	$response = new ServiceResult();
	$result = DBProductServiceFacade::queryUserInfo($userName);

	$data = json_decode($result, true);
	$response->setData($data['data'][0]);
	return $response;
}

function authUserInfo()
{
	$userName = 'cai';
	$password = 'md5md5';
	$response = new ServiceResult();
	$isSuccess = false;
	$token = '';
	$data = array();
//	$result = DBServiceFacade::queryUserInfo($username);
	$password = md5(md5($pwd));

	$result = queryUserByName($userName);

	$data = $result->getData();

	$userName = $data['name'];



//	$result = UserAuthService::queryUserInfo($param=array());

//	$res = json_decode(json_encode($result), true)[0];

//	$data = $result['data'][0];




	$payload = array(
		'iat' => time(),
		'exp' => time() + 60*30,
		'username' => $userName
	);
	$secretKey = 'orion';
	if (!empty($data) && $data['name'] == $userName && $data['password'] == $password)
	{
		$isSuccess = true;
		$token = JWTHelper::genJWT($payload, $secretKey);
	}
	$data['isSuccess'] = $isSuccess;
	$data['token'] = $token;
	$response->setData($data);
	return $response;
}

function generateMd5Pw()
{
    $paramPassword = '2wsx#EDC';
    $md5pw = md5(md5($paramPassword));
    print_r($md5pw);
}