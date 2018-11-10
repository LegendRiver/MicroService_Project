<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/25
 * Time: 上午9:38
 */

namespace CommonMoudle\Service;


class ServiceBaseConstant
{
    const OK = 200;

    const BODY_STATE_CODE = 'statusCode';
    const BODY_MESSAGE = 'message';
    const BODY_DATA = 'data';

    const CONTENT_TYPE_FORM_DATA = 'multipart/form-data';
    const CONTENT_TYPE_JSON = 'application/json';

    const SERVER_FIELD_REQUEST_METHOD = 'REQUEST_METHOD';
    const SERVER_FIELD_AUTHENTICATE = 'PHP_AUTH_DIGEST';
    const SERVER_FIELD_CONTENT_TYPE = 'CONTENT_TYPE';

    const REQUEST_METHOD_POST = 'POST';

    const INPUT_CONTENT = 'php://input';
}