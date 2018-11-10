<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'common_moudles/vendor/autoload.php';

use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Http\HttpClient;

init();

function init()
{
    initTimeZone();
    initLogger();
    initHttpServer();
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
    ServerLogger::instance()->setLoggerModule('duplicate_ad_tool');
}

function initHttpServer()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $serverConfFile = $confDir . DIRECTORY_SEPARATOR . 'dependence_service_conf.json';
    HttpClient::instance()->initServerInfo($serverConfFile);
}