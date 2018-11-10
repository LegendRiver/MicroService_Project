<?php
namespace CommonMoudle\Constant;

class LogConstant
{
    const LOGGER_LEVEL_EMERGENCY = 0;
    const LOGGER_LEVEL_ALERT = 1;
    const LOGGER_LEVEL_CRITICAL = 2;
    const LOGGER_LEVEL_ERROR = 3;
    const LOGGER_LEVEL_WARNING = 4;
    const LOGGER_LEVEL_INFO = 5;
    const LOGGER_LEVEL_DEBUG = 6;

    const LOGGER_DEFAULT_LOG_PATH = "log";

    const LOGGER_CONF_FIELD_LIST = 'logConfig';
    const LOGGER_CONF_FIELD_MODULE = 'logModule';
    const LOGGER_CONF_FIELD_PATH = 'logPath';
    const LOGGER_CONF_FIELD_LEVEL = 'logLevel';

    const LOGGER_TYPE_VALUE_DEFAULT = 'default';
}