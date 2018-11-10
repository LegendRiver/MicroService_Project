<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'common_moudles/vendor/autoload.php';

use CommonMoudle\Logger\ServerLogger;

initOrionMoudle();

function initOrionMoudle()
{
    initTimeZone();
    initLogger();
}

function initTimeZone()
{
    date_default_timezone_set("Asia/Shanghai");
}

function initLogger()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $logConfFile = $confDir . DIRECTORY_SEPARATOR . 'log_conf.json';
    ServerLogger::instance()->initLogger($logConfFile);
    ServerLogger::instance()->setLoggerModule('orion_service');
}