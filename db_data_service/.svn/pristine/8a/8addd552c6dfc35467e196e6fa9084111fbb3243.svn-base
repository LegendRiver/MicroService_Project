<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use DBService\Manager\OrionDB\ProductDBManager;

productQueryTest();

function productQueryTest()
{
    $db = new ProductDBManager();
    $result = $db->selectValidProduct();
    print_r($result);
}