<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use CommonMoudle\Service\ServiceInitializer;
use CommonMoudle\Service\RESTCore;

RESTCore::setHeader();
init();

echo RESTCore::callService();

function init()
{
    initDBService();
}

function initDBService()
{
    $confDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src/conf';
    $exportConfFile = $confDir . DIRECTORY_SEPARATOR . 'service_conf.json';
    $requestParamFile = $confDir . DIRECTORY_SEPARATOR . 'service_request_param_conf.json';
    ServiceInitializer::exportService($exportConfFile);
    ServiceInitializer::initRequestParamMap($requestParamFile);
}