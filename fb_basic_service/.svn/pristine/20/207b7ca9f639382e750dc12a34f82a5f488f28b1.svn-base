<?php
namespace FBBasicService\Manager;

use CommonMoudle\Constant\LogConstant;
use FacebookAds\Object\ProductCatalog;
use FacebookAds\Object\Fields\ProductCatalogFields;
use FBBasicService\Common\FBLogger;

class ProductCatalogManager
{
    public static function createCatalog($businessId)
    {
        try
        {
            $productCatalog = new ProductCatalog(null, $businessId);

            $productCatalog->setData(array(
                ProductCatalogFields::NAME => "Catalog",
            ));

            $productCatalog->create();

            return $productCatalog->{ProductCatalogFields::NAME};
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }
}