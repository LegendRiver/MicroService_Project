<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/10
 * Time: 上午10:47
 */

namespace DBService\Manager\OrionDB;


use CommonMoudle\Constant\DBConstant;
use CommonMoudle\Constant\LogConstant;
use CommonMoudle\DBManager\DBParameter;
use CommonMoudle\Logger\ServerLogger;
use DBService\Common\AbstractDBManager;
use DBService\DBField\OrionDB\AdvertiserDBFields;
use DBService\DBField\OrionDB\OrionTableNameConstants;
use DBService\DBField\OrionDB\PlatformDBFields;
use DBService\DBField\OrionDB\ProductDBFields;
use DBService\DBField\OrionDB\ProductDeliveryMapFields;
use DBService\DBField\OrionDB\SelectAliasConstants;
use DBService\Entity\OrionDB\ProductInfoEntity;

class ProductDBManager extends AbstractDBManager
{
    private $productTableName;

    private $dbManager;

    public function __construct()
    {
        $this->productTableName = OrionTableNameConstants::PRODUCT_INFO;
        $this->dbManager = OrionDBConnection::instance()->getOrionDBManager();
    }
    public function selectAllProduct($fieldArray = array())
    {
        if(empty($fieldArray))
        {
            $fieldArray = ProductDBFields::getInstance()->getValues();
        }

        $selectParamMap = array();
        $recordRows = $this->dbManager->selectDbRecord($this->productTableName, $fieldArray, $selectParamMap);
        if(false === $recordRows)
        {
            return false;
        }

        $entityArray = array();
        foreach($recordRows as $row)
        {
            $entityArray[] = $this->buildDBEntity($row);
        }

        return $entityArray;
    }

    public function selectValidProduct()
    {
        try
        {
            $selectSql = $this->buildProductJoinSql();

            $validStatus = 1;
            $platform =1;

            $selectParams = array();
            $deliveryStatusParams = new DBParameter(ProductDeliveryMapFields::STATUS, $validStatus, DBConstant::DB_TYPE_INTEGER);
            $deliveryPlatformParams = new DBParameter(ProductDeliveryMapFields::PLATFORM_ID, $platform, DBConstant::DB_TYPE_INTEGER);
            $selectParams[] = $deliveryStatusParams;
            $selectParams[] = $deliveryPlatformParams;

            $recordRows = $this->dbManager->querySql($selectSql, $selectParams);
            if ($recordRows === false)
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, sprintf("Execute sql <%s> failed.", print_r($selectParams, true)));
                return false;
            }
        }
        catch (\Exception $e)
        {
            ServerLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

        return $recordRows;
    }

    private function buildProductJoinSql()
    {
        $sql = 'SELECT product.'. ProductDBFields::ID .', ';
        $sql .= 'product.' . ProductDBFields::NAME . ' as ' . SelectAliasConstants::PRODUCT_NAME . ', ';
        $sql .= 'product.'. ProductDBFields::LOGO_PATH . ', ';
        $sql .= 'advertiser.' . AdvertiserDBFields::NAME . ' as ' . SelectAliasConstants::ADVERTISER_NAME . ', ';
        $sql .= 'delivery.' . ProductDeliveryMapFields::PLATFORM_ID . ', ';
        $sql .= 'platform.' . PlatformDBFields::NAME . ' as ' . SelectAliasConstants::PLATFORM_NAME . ' ';
        $sql .= 'FROM ' . OrionTableNameConstants::PRODUCT_INFO . ' as product ';
        $sql .= 'inner join ' . OrionTableNameConstants::PRODUCT_PLATFORM_DELIVERY . ' as delivery ';
        $sql .= 'on product.' . ProductDBFields::ID . ' = delivery.' . ProductDeliveryMapFields::PRODUCT_ID . ' ';
        $sql .= 'inner join ' . OrionTableNameConstants::ADVERTISER_INFO . ' as advertiser ';
        $sql .= 'on product.' .ProductDBFields::ADVERTISER_ID . ' = advertiser.' . AdvertiserDBFields::ID . ' ';
        $sql .= 'inner join ' .OrionTableNameConstants::PLATFORM_INFO . ' as platform ';
        $sql .= 'on delivery.' .ProductDeliveryMapFields::PLATFORM_ID . ' = platform.' . PlatformDBFields::ID . ' ';
        $sql .= 'where delivery.' . ProductDeliveryMapFields::STATUS . ' = ?';
        $sql .= ' and ' . ProductDeliveryMapFields::PLATFORM_ID . ' = ?';

        return $sql;
    }

    protected function initEntityCondition()
    {
        $this->dbEntityInstance = new ProductInfoEntity();

        $this->field2FunctionName = array(
            ProductDBFields::ID => 'setId',
            ProductDBFields::NAME => 'setName',
            ProductDBFields::ADVERTISER_ID => 'setAdvertiserId',
            ProductDBFields::LOGO_PATH => 'setImagePath',
        );
    }

}