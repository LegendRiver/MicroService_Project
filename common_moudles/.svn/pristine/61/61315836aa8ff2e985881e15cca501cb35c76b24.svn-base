<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/21
 * Time: 上午10:15
 */

namespace CommonMoudle\Helper;


class JsonFileHelper
{
    public static function readJsonFile($filePath)
    {
        $jsonString = file_get_contents($filePath);
        if(false === $jsonString)
        {
            return false;
        }

        $jsonArray = self::decodeJsonString($jsonString);

        return $jsonArray;
    }

    public static function writeJsonFile($objArray, $fileName)
    {
        //ksort($objArray, SORT_STRING);
        $jsonString = json_encode($objArray, JSON_PRETTY_PRINT);
        file_put_contents($fileName, $jsonString . PHP_EOL, FILE_APPEND);
    }

    public static function decodeJsonString($jsonString)
    {
        $jsonArray = json_decode($jsonString, true);
        $jsonErrorCode = json_last_error();
        if(JSON_ERROR_NONE != $jsonErrorCode)
        {
            return false;
        }

        return $jsonArray;
    }

    public static function decodeJsonCheck($jsonCode)
    {
        switch ($jsonCode) {
            case JSON_ERROR_NONE:
                return ' - No errors';
                break;
            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                return ' - Unknown error';
                break;
        }
    }
}