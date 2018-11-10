<?php
namespace CommonMoudle\Helper;
use CommonMoudle\Constant\DBConstant;

class DBHelper
{
    public static function buildUpdateSql($tableName, $fieldArray, $whereArray)
    {
        $strField = static::getSqlEqualString($fieldArray, DBConstant::SQL_COMMA_SPACE);
        $strWhere = static::getSqlEqualString($whereArray, DBConstant::SQL_AND_SPACE);

        if(empty($strField) || empty($strWhere))
        {
            return '';
        }

        return sprintf(DBConstant::UPDATE_QUERY_FORMAT, $tableName, $strField, $strWhere);
    }

    public static function buildSelectSql($tableName, $fieldArray, $whereArray = array())
    {
        if(empty($fieldArray))
        {
            return '';
        }
        $fieldStr = implode(DBConstant::SQL_COMMA_SPACE, $fieldArray);

        $selectSql = sprintf(DBConstant::SELECT_QUERY_FORMAT, $fieldStr, $tableName);

        if(!empty($whereArray))
        {
            $whereStr = static::getSqlEqualString($whereArray, DBConstant::SQL_AND_SPACE);
            $selectSql .= DBConstant::SQL_WHERE_SPACE;
            $selectSql .= $whereStr;
        }

        return $selectSql;
    }

    private static function getSqlEqualString($fieldArray, $separator)
    {
        $fieldCount = count($fieldArray);

        if($fieldCount === 0)
        {
            return '';
        }

        $updateEqualArray = array_fill(0, $fieldCount, DBConstant::SQL_EQUAL_FORMAT);

        $strUpdateFormat = implode($separator, $updateEqualArray);

        $strField = vsprintf($strUpdateFormat, $fieldArray);

        return $strField;
    }
}