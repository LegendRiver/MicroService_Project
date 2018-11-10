<?php
/**
 * Created by PhpStorm.
 * User: caijia
 * Date: 18/1/25
 * Time: 下午2:39
 */

namespace OrionService\Constant;


class QueryParamConstant
{
    //ProductService
    const PRODUCT_ID = 'productId';
    const START_DATE = 'startDate';
    const END_DATE = 'endDate';

    //UserAuthService
    const USER_NAME = 'userName';
    const ID = 'id';
    const USER_PASSWORD = 'password';

    //UploadMaterialService
    const UPLOAD_FILE_DATA = 'dataContent';
    const UPLOAD_USER_NAME = 'userName';
    const UPLOAD_USER_ID = 'userId';
    const UPLOAD_TASK_NAME = 'taskName';
    const UPLOAD_POST_FILE_NAME = 'filename';

	//DuplicateTaskService
	const DUPLICATE_TASK_ID = 'id';
	const DUPLICATE_TASK_USER_ID = 'userId';
	const DUPLICATE_TASK_NAME = 'taskName';
	const DUPLICATE_TASK_STATUS = 'status';
	const DUPLICATE_TASK_ROOT_PATH = 'rootPath';
	const DUPLICATE_TASK_JSON_NAME = 'jsonName';
	const DUPLICATE_TASK_CREATE_TIME = 'createTime';
	const DUPLICATE_TASK_COMPLETE_TIME = 'completeTime';
}