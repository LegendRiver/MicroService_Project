<?php

namespace OrionService\Business;

use OrionService\Constant\OrionServiceStateCode;
use OrionService\Constant\QueryParamConstant;
use OrionService\Common\OrionServiceResult;
use OrionService\DB\DBDuplicateTaskServiceFacade;

class DuplicateTaskService
{
	public function selectTaskByUserId($param)
	{
		$response = new OrionServiceResult();
		if(!array_key_exists(QueryParamConstant::DUPLICATE_TASK_USER_ID, $param))
		{
			$response->setErrorCode(OrionServiceStateCode::DUPLICATE_TASK_USER_ID_EMPTY);
			return $response;
		}
		$userId = $param[QueryParamConstant::DUPLICATE_TASK_USER_ID];
		$task = array();
		$task = DBDuplicateTaskServiceFacade::selectTaskByUserId($userId);
		if (empty($task))
		{
			$response->setErrorCode(OrionServiceStateCode::QUERY_TASK_BY_USER_ID_FAILED);
			return $response;
		}
		$response->setData($task);
		return $response;
	}
}