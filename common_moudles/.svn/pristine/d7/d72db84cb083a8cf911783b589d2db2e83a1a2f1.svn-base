<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/9
 * Time: 上午10:44
 */

namespace CommonMoudle\DBManager;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Helper\DBHelper;
use CommonMoudle\Logger\ServerLogger;

/**
 * Class AbstractDBManager
 * @package CommonMoudle\DBManager
 * 1. 目前只支持单个数据库，后续需要管理多数据库，再根据实际需要重构
 * 2. 后续要考虑实现连接池
 * 3. 后续要 事务实现
 */
class BaseDBManager
{
    private $dbHandler=null;
    private $dbName;

    public function __construct($singleDBConfFile)
    {
        $this->dbHandler = new MysqlHandler($singleDBConfFile);
        $this->dbName = $this->dbHandler->getDatabaseName();
    }

    public function selectDbRecord($tableName, $fieldArray, $whereParamMap)
    {
        $whereFieldArray = array_keys($whereParamMap);
        $selectSql = DBHelper::buildSelectSql($tableName, $fieldArray, $whereFieldArray);
        if(empty($selectSql))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to build the select sql.');
            return false;
        }

        $selectParameters = array_values($whereParamMap);

        return $this->querySql($selectSql, $selectParameters);
    }

    public function querySql($selectSql, $selectParameters)
    {
        $recordRows = $this->dbHandler->querySql($selectSql, $selectParameters);
        if($recordRows === false)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, sprintf("Execute sql <%s> failed.", $selectSql));
            return false;
        }

        return $recordRows;
    }

    public function updateDbRecord($tableName, $fieldParamMap, $whereParamMap)
    {
        $updateField = array_keys($fieldParamMap);
        $whereField = array_keys($whereParamMap);

        $updateSql = DBHelper::buildUpdateSql($tableName, $updateField, $whereField);
        if(empty($updateSql))
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to build the update sql.');
            return false;
        }

        $updateParameters = array_values($fieldParamMap);
        $whereParameters = array_values($whereParamMap);
        $sqlParameters = array_merge($updateParameters, $whereParameters);

        return $this->executeSql($updateSql, $sqlParameters);
    }

    public function executeSql($sql, $params)
    {
        $rowNum = $this->dbHandler->executeSql($sql, $params);
        if(false === $rowNum)
        {
            $message = sprintf("executeSql sql <%s> failed.", $sql);
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, $message);
            return false;
        }

        return $rowNum;
    }
}