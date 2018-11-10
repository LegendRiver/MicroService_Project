<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/21
 * Time: 下午4:14
 */

namespace CommonMoudle\Helper;


class ExcelFileHelper
{
    public static function readExcelFile($filePath)
    {
        $excelObject = \PHPExcel_IOFactory::load($filePath);
        $sheet = $excelObject->getSheet(0);
        if(empty($sheet))
        {
            return array();
        }
        return $sheet->toArray();
    }
}