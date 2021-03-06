<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use CommonMoudle\Service\ServiceInitializer;
use CommonMoudle\Service\RESTCore;
use CommonMoudle\Http\HttpClient;

callService();

function callService()
{
    RESTCore::setHeader();
    init();
    echo RESTCore::callService();
}

function init()
{
//    initOrionMockService();
    initOrionService();
    initHttpServer();
}

function initOrionMockService()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/Mock';
    $exportConfFile = $confDir . DIRECTORY_SEPARATOR . 'mock_service_conf.json';
    $requestParamFile = $confDir . DIRECTORY_SEPARATOR . 'mock_request_map.json';
    ServiceInitializer::exportService($exportConfFile);
    ServiceInitializer::initRequestParamMap($requestParamFile);
}

function initOrionService()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $exportConfFile = $confDir . DIRECTORY_SEPARATOR . 'service_conf.json';
    $requestParamFile = $confDir . DIRECTORY_SEPARATOR . 'service_request_param_conf.json';
    ServiceInitializer::exportService($exportConfFile);
    ServiceInitializer::initRequestParamMap($requestParamFile);
}

function initHttpServer()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $serverConfFile = $confDir . DIRECTORY_SEPARATOR . 'dependence_service_conf.json';
    HttpClient::instance()->initServerInfo($serverConfFile);
}
