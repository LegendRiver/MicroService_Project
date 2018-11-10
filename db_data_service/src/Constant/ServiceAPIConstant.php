<?php
namespace DBService\Constant;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/10
 * Time: 下午2:01
 */
class ServiceAPIConstant
{
    const PRODUCT_ID = 'id';
    const PRODUCT_NAME = 'name';
    const PRODUCT_LOGO_PATH = 'imagePath';
    const PRODUCT_ACCOUNT_LIST = 'accountList';
    const PRODUCT_ADVERTISER = 'advertiser';

    const ACCOUNT_ATTRIBUTE_ID = 'id';
    const ACCOUNT_ATTRIBUTE_PRODUCT_ID = 'productId';
    const ACCOUNT_ATTRIBUTE_ACCOUNT_ID = 'accountId';
    const ACCOUNT_ATTRIBUTE_NAME = 'name';
    const ACCOUNT_ATTRIBUTE_AGENCY = 'agency';
    const ACCOUNT_ATTRIBUTE_IS_DISPLAY = 'isDisplay';

    const DUPLICATE_TASK_ID = 'id';
    const DUPLICATE_TASK_TASK_NAME = 'task_name';
    const DUPLICATE_TASK_CREATE_TIME = 'create_time';
    const DUPLICATE_TASK_COMPLETE_TIME = 'complete_time';
    const DUPLICATE_TASK_STATUS = 'status';
    const DUPLICATE_TASK_STATUS_DESCRIPTION = 'description';
    const DUPLICATE_TASK_ROOT_PATH = 'root_path';
    const DUPLICATE_TASK_JOSN_NAME = 'json_name';
    const DUPLICATE_TASK_USER_ID = 'user_id';

    const USER_INFO_NAME = 'name';
    const USER_INFO_EMAIL = 'email';
}