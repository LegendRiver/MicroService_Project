<?php
namespace CommonMoudle\Logger;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Constant\ErrorCodeConstant;

class BasicLog
{
    private $logLevel = LogConstant::LOGGER_LEVEL_DEBUG;
    private $logPath;
    private $LogLevelNameTable = null;

    public function  __construct($loggerConfig)
    {
        $this->logLevel = $loggerConfig[LogConstant::LOGGER_CONF_FIELD_LEVEL];
        $this->logPath = $loggerConfig[LogConstant::LOGGER_CONF_FIELD_PATH];
        $this->LogLevelNameTable = array();
        $this->initLogLevelNameTable();

        if(empty($this->logPath))
        {
            $this->logPath = LogConstant::LOGGER_DEFAULT_LOG_PATH;
        }
    }

    public function __destruct()
    {
        unset($this);
    }

    private function initLogLevelNameTable()
    {
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_EMERGENCY] = 'Emergency';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_ALERT] = 'Alert';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_CRITICAL] = 'Critical';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_ERROR] = 'Error';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_WARNING] = 'Warning';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_INFO] = 'Info';
        $this->LogLevelNameTable[LogConstant::LOGGER_LEVEL_DEBUG] = 'Debug';
    }

    private function getLogLevelName($logLevel)
    {
        if(array_key_exists($logLevel, $this->LogLevelNameTable))
        {
            return $this->LogLevelNameTable[$logLevel];
        }

        return ErrorCodeConstant::LOG_UNKNOWN;
    }

    public function writeLog($logLevel, $file, $function, $line, $msg)
    {
        $logFile = sprintf("%s/%s", $this->logPath, sprintf("%s.log", date("Y-m-d")));

        if (!file_exists($this->logPath))
        {
            if (!mkdir($this->logPath, 0777, true))
            {
                return ErrorCodeConstant::CREATE_LOG_DIR_FAIL;
            }
        }

        if ($this->logLevel >= $logLevel)
        {
            $currentTime = date("Y-m-d H:i:s", time());
            $logMsg = sprintf("[%s][%s][%s,%s,%d][%s]\n", strtoupper($this->getLogLevelName($logLevel)), $currentTime, $file, $function, $line, $msg);
            $result = error_log($logMsg, 3, $logFile);
            if (!$result)
            {
                return ErrorCodeConstant::WRITE_LOG_TO_FILE_FAIL;
            }
            return ErrorCodeConstant::LOG_OK;
        }
        return ErrorCodeConstant::LOG_OK;
    }

}