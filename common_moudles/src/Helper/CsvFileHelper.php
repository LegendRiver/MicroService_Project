<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/21
 * Time: 上午10:27
 */

namespace CommonMoudle\Helper;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;

class CsvFileHelper
{
    public static function readCsv($fileName, $length = 1000, $delimiter = ',')
    {
        $fileContent = array();
        try
        {
            if (($handle = fopen($fileName, "r")) !== false)
            {
                while (($data = fgetcsv($handle, $length, $delimiter)) !== false)
                {
                    $fileContent[] = $data;
                }

                fclose($handle);
            }
            else
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to open the file :' . $fileName);
            }

        }
        catch (\Exception $e)
        {
            ServerLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            if(!empty($handle))
            {
                fclose($handle);
            }
            return $fileContent;
        }

        return $fileContent;
    }

    public static function saveCsv($fileName, $contentArray, $delimiter=',')
    {
        /*$list = array (
            array('aaa', 'bbb', 'ccc', 'dddd'),
            array('123', '456', '789'),
            array('"aaa"', '"bbb"')
        );*/

        try
        {
            $fp = fopen($fileName, 'a');

            foreach ($contentArray as $fields)
            {
                fputcsv($fp, $fields, $delimiter);
            }

            fclose($fp);
        }
        catch (\Exception $e)
        {
            ServerLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            if(!empty($fp))
            {
                fclose($fp);
            }
        }
    }
}