<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/20
 * Time: 下午8:50
 */

namespace CommonMoudle\Constant;


class ErrorCodeConstant
{
    const LOG_OK = 0;
    const LOG_ERROR = 1;
    const LOG_UNKNOWN = 2;

    const CREATE_LOG_DIR_FAIL = 10001;
    const WRITE_LOG_TO_FILE_FAIL = 10002;

    const CODE_PARSE_PARAM_FAILED = 11001;
    const CODE_CALL_SERVICE_EXCEPTION = 11005;
    const CODE_CALL_SERVICE_EMPTY = 11010;

    const CHECK_TOKEN_FAILED = 11015;
}