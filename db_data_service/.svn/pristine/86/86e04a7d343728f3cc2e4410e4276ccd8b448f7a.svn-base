<?php
namespace DBService\Common;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/9
 * Time: 下午9:32
 */
abstract class AbstractDBManager
{
    protected $dbEntityInstance;

    protected $field2FunctionName;

    protected function buildDBEntity($selectRow)
    {
        $this->initEntityCondition();

        foreach ($this->field2FunctionName as $fieldName => $functionName)
        {
            if(!array_key_exists($fieldName, $selectRow))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_DEBUG, 'The field: ' . $fieldName . 'does not exist in db result');
                continue;
            }

            if(!method_exists($this->dbEntityInstance, $functionName))
            {
                $className = get_class($this->dbEntityInstance);
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The method: ' . $functionName .
                    'does not exist in class: ' . $className);
                continue;
            }

            call_user_func_array(array($this->dbEntityInstance, $functionName), array($selectRow[$fieldName]));
        }

        return $this->dbEntityInstance;
    }

    protected abstract function initEntityCondition();
}