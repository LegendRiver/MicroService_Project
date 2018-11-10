<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/2/2
 * Time: 上午11:23
 */

namespace CommonMoudle\Helper;


use CommonMoudle\Constant\CommonConstant;

class FormDataHelper
{
    public static function parseFormData($dataHandler)
    {
        if(empty($dataHandler))
        {
            return array();
        }

        $dataInfo = array();
        $dataInfo[CommonConstant::FORM_DATA_CONTENT] = '';
        $boundary = null;
        $dataStart = false;
        while(($currentLine = fgets($dataHandler)) !== false)
        {
            if(strpos($currentLine, '--') === 0)
            {
                if(is_null($boundary))
                {
                    $boundary = rtrim($currentLine);
                    continue;
                }
                else
                {
                    if(strpos($currentLine, $boundary) !== false)
                    {
                        break;
                    }
                }
            }

            if($dataStart)
            {
                if(!empty($currentLine))
                {
                    $dataInfo[CommonConstant::FORM_DATA_CONTENT] .= $currentLine;
                }
                continue;
            }

            $lineContent = rtrim($currentLine);
            if($lineContent == '')
            {
                $dataStart = true;
                continue;
            }
            else
            {
                $dataStart = false;
            }

            $delimIndex = strpos($lineContent, ':');
            if($delimIndex !== false)
            {
               $headerInfo = static::parseDataHeader($lineContent);
               $dataInfo = array_merge($dataInfo, $headerInfo);
            }
        }

        return $dataInfo;
    }

    private static function parseDataHeader($lineContent)
    {
        $headerInfo = array();
        $contentArray = preg_split('/;/', $lineContent, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($contentArray as $fieldStr)
        {
            if(strpos($fieldStr, ':') !== false)
            {
                $fieldKeyValue = preg_split('/:/', $fieldStr, -1, PREG_SPLIT_NO_EMPTY);
                $headerInfo[trim($fieldKeyValue[0])] = trim($fieldKeyValue[1], "\" ");
                continue;
            }

            if(strpos($fieldStr, '=') !== false)
            {
                $fieldKeyValue = preg_split('/=/', $fieldStr, -1, PREG_SPLIT_NO_EMPTY);
                $headerInfo[trim($fieldKeyValue[0])] = trim($fieldKeyValue[1], "\" ");
                continue;
            }
        }

        return $headerInfo;
    }
}