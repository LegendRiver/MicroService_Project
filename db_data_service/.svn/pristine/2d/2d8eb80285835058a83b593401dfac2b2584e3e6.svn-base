<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/18
 * Time: 下午3:19
 */

namespace DBService\Common;


use CommonMoudle\Service\ServiceResult;
use DBService\Constant\OrionDBStatusCode;

class OrionDBServiceResult extends ServiceResult
{
    public function getSubMessage()
    {
        return array
		(
			OrionDBStatusCode::USER_NAME_EMPTY => 'select table user_info needs a parameter, just like name, but the parameter is null.',
			OrionDBStatusCode::SELECT_USER_NAME_FAIL => 'Failed to select table user_info.',

            OrionDBStatusCode::PARAM_NULL_PRODUCT_ID => 'The query param product id is null',
            OrionDBStatusCode::FAILED_DB_QUERY_ACCOUNT => 'Failed to query account by product ID',
            OrionDBStatusCode::FAILED_QUERY_PRODUCT => 'Failed to query valid product',

			OrionDBStatusCode::INSERT_DUPLICATE_TASK_NAME_EMPTY => 'Failed to insert duplicate_task, parameter taskName missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_USER_ID_EMPTY => 'Failed to insert duplicate_task, parameter taskName missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_STATUS_EMPTY => 'Failed to insert duplicate_task, parameter userId missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_ROOT_PATH_EMPTY => 'Failed to insert duplicate_task, parameter rootPath missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_JSON_NAME_EMPTY => 'Failed to insert duplicate_task, parameter jsonName missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_CREATE_TIME_EMPTY => 'Failed to insert duplicate_task, parameter createTime missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_COMPLETE_TIME_EMPTY => 'Failed to insert duplicate_task, parameter completeTime missing.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_FAIL => 'Failed to insert duplicate_task, maybe sql syntex error.',


			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_NAME_EMPTY => 'Failed to update duplicate_task, parameter taskName missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_USER_ID_EMPTY => 'Failed to update duplicate_task, parameter taskName missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_STATUS_EMPTY => 'Failed to update duplicate_task, parameter userId missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_ROOT_PATH_EMPTY => 'Failed to update duplicate_task, parameter rootPath missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_JSON_NAME_EMPTY => 'Failed to update duplicate_task, parameter jsonName missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_CREATE_TIME_EMPTY => 'Failed to update duplicate_task, parameter createTime missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_COMPLETE_TIME_EMPTY => 'Failed to update duplicate_task, parameter completeTime missing.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_FAIL => 'Failed to update duplicate_task, maybe sql syntex error in db_data_service.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_REPEAT => 'You have updated duplicate_task once in db_data_service.',

			OrionDBStatusCode::INSERT_DUPLICATE_TASK_VALUE_NULL => 'The insert value could not be null.',
			OrionDBStatusCode::DUPLICATE_TASK_COUNT_ERROR => 'The count is less than 0.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_COUNT_ERROR => 'The insert value count is not equel to the dbCount.',
			OrionDBStatusCode::INSERT_DUPLICATE_TASK_FAIL => 'Insert duplicate task fail.',
			OrionDBStatusCode::UPDATE_DUPLICATE_TASK_PARAM_NULL => 'The parameter is not in the table duplicate_task while updating the table.',
			OrionDBStatusCode::SELECT_DUPLICATE_TASK_USER_ID_EMPTY => 'Failed to accept parameter userId in db_data_service.',
			OrionDBStatusCode::SELECT_DUMPLICATE_TASK_FAIL => 'Failed to select duplicate_task by userId in db_data_service.',
			OrionDBStatusCode::SELECT_DUPLICATE_TASK_STATUS_EMPTY => 'Failed to accept parameter status in db_data_service.',
			OrionDBStatusCode::SELECT_DUMPLICATE_TASK_FAIL_BY_STATUS => 'Failed to select duplicate_task by status in db_data_service.',
			OrionDBStatusCode::SELECT_ALL_DUMPLICATE_TASK_FAIL => 'Failed to select all duplicate_task in db_data_service.',
        );
    }
}