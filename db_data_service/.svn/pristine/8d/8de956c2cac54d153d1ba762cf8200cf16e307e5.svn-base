<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/9
 * Time: 下午3:04
 */

namespace DBService\Manager\OrionDB;

use CommonMoudle\Constant\DBConstant;
use CommonMoudle\DBManager\DBParameter;
use DBService\Common\AbstractDBManager;
use DBService\DBField\OrionDB\AccountDBFields;
use DBService\DBField\OrionDB\OrionTableNameConstants;
use DBService\Entity\OrionDB\AccountInfoEntity;

class AccountDBManager extends AbstractDBManager
{
    private $accountTableName;

    private $dbManager;

    public function __construct()
    {
        $this->accountTableName = OrionTableNameConstants::ACCOUNT_INFO;
        $this->dbManager = OrionDBConnection::instance()->getOrionDBManager();
    }

    public function selectAccountByProductId($productId, $fieldArray = array())
    {
        if(empty($fieldArray))
        {
            $fieldArray = AccountDBFields::getInstance()->getValues();
        }

        $selectParamMap = array();
        $productIdParam = new DBParameter(AccountDBFields::PRODUCT_ID, $productId, DBConstant::DB_TYPE_INTEGER);
        $selectParamMap[AccountDBFields::PRODUCT_ID] = $productIdParam;

        $recordRows = $this->dbManager->selectDbRecord($this->accountTableName, $fieldArray, $selectParamMap);
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

    protected function initEntityCondition()
    {
        $this->dbEntityInstance = new AccountInfoEntity();

        $this->field2FunctionName = array(
            AccountDBFields::ID => 'setId',
            AccountDBFields::NAME => 'setName',
            AccountDBFields::PRODUCT_ID => 'setProductId',
            AccountDBFields::ACCOUNT_ID => 'setAccountId',
            AccountDBFields::AGENCY => 'setAgency',
            AccountDBFields::IS_DISPLAY => 'setIsDisplay',
        );
    }

}