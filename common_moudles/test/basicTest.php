<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
use CommonMoudle\Helper\FileHelper;
use CommonMoudle\Helper\ExcelFileHelper;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Http\Curl\CurlManager;
use CommonMoudle\Http\RequestParameter;

date_default_timezone_set("Asia/Shanghai");

unzipTest();
function getFileNameTest()
{
    $path = '/dsdfds/sdfsf/ddfds.csv';
    $fileName = FileHelper::getFileNameFromPath($path);
    print_r($fileName);
}

function excelTest()
{
    ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, "sdfsfaf");
    ExcelFileHelper::readExcelFile('a.xlsx');
}

function requestTest()
{
    $request = new RequestInfo();
    $request->setServer('127.0.0.1');
    $request->setPort('8050');
    $request->setEndpoint('productBasicInfo');
    $request->setMethod(RequestInfo::METHOD_GET);

    $params = new RequestParameter();
    $paramArray=array();
    $params->enhance();

    $curlManager  = new CurlManager();
    $response = $curlManager->sendRequest($request);

}

function unzipTest()
{
    $zipFile = '/Users/yuanyuan/Downloads/flash0202上传.zip';
    $desDir = '/Users/yuanyuan/Downloads';
    FileHelper::unzipFile($zipFile, $desDir);
}