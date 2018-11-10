<?php
namespace OrionService\DB;

use CommonMoudle\Http\HttpClient;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Service\ServiceBaseConstant;
use OrionService\Constant\CommonConstant;
use OrionService\Constant\QueryParamConstant;
use OrionService\Constant\ServiceEndpointConstant;

class DBUserServiceFacade
{
	public static function queryUserByName($userName)
	{
		$queryParam =
			[
				QueryParamConstant::USER_NAME => $userName
			];
		$res = HttpClient::instance()->sendRequest(CommonConstant::DB_SERVER_KEY, RequestInfo::METHOD_GET, ServiceEndpointConstant::QUERY_DB_USER_INFO, $queryParam);
		$result = $res->getBody();
		$data = json_decode($result, true);
		return $data[ServiceBaseConstant::BODY_DATA][0];
	}
}