<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/2
 * Time: 上午10:33
 */

namespace CommonMoudle\Constant;


class ServiceConstant
{
    const CONF_ROOT = 'serviceConfig';
    const CONF_SERVICE_NAME = 'serviceName';
    const CONF_CLASS_INFO = 'classInfo';
    const CONF_CLASS_NAME = 'className';
    const CONF_FUNCTION_LIST = 'functionList';


    const PARAM_CONF_ROOT = 'requestParamMap';
    const PARAM_CONF_SERVICE_NAME = 'serviceName';
    const PARAM_CONF_CLASS_NAME = 'className';
    const PARAM_CONF_FUNCTION_NAME = 'functionName';
    const PARAM_CONF_IS_AUTH = 'isAuth';

    const URL_PARAM_ALIAS = 'ServiceAlias';

    const PARSED_PARAM_SERVICE_NAME = 'SERVICE_NAME';
    const PARSED_PARAM_CLASS_NAME = 'CLASS_NAME';
    const PARSED_PARAM_FUNCTION_NAME = 'FUNCTION_NAME';
    const PARSED_PARAM_AUTHENTICATION = 'IS_AUTHENTICATION';

    const SERVICE_TOKEN = 'sessionToken';

}