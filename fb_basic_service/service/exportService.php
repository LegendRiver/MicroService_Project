<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use CommonMoudle\Service\ServiceInitializer;
use CommonMoudle\Service\RESTCore;
use FBBasicService\Common\FBPathManager;
use FBBasicService\Common\FBLogger;
use FBBasicService\Common\APIInit;

RESTCore::setHeader();
init();

echo RESTCore::callService();

function init()
{
    initFBLogger();
    initFBAPI();
    initFBService();
//    initMockService();
}

function initMockService()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/Mock';
    $exportConfFile = $confDir . DIRECTORY_SEPARATOR . 'mock_service_conf.json';
    $requestParamFile = $confDir . DIRECTORY_SEPARATOR . 'mock_request_map.json';
    ServiceInitializer::exportService($exportConfFile);
    ServiceInitializer::initRequestParamMap($requestParamFile);
}

function initFBService()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $exportConfFile = $confDir . DIRECTORY_SEPARATOR . 'service_conf.json';
    $requestParamFile = $confDir . DIRECTORY_SEPARATOR . 'service_request_param_conf.json';
    ServiceInitializer::exportService($exportConfFile);
    ServiceInitializer::initRequestParamMap($requestParamFile);
}

function initFBLogger()
{
    $fbLoggerConfFile = FBPathManager::instance()->getLogConfigPath();
    FBLogger::instance()->initLogger($fbLoggerConfFile);
    FBLogger::instance()->setLoggerModule('fb_basic_service');
}

function initFBAPI()
{
    APIInit::init();
}

