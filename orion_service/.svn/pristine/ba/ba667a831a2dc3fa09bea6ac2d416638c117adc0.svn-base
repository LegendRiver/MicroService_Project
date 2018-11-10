<?php
namespace OrionService\Mock;

use CommonMoudle\Service\ServiceResult;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/15
 * Time: 下午3:21
 */
class MockService
{
   public function queryProductInfo($param)
   {
       $response = new ServiceResult();
       $data = array(
       1 => array(
           'id' => 1,
           'name' => 'Opera Mini',
           'advertiser' => '360 Security',
           'imagePath' => './resources/logo/opera_mini.png',
           'accountList' => array(10, 11, 52, 67)
       ),
       2 => array(
           'id' => 2,
           'name' => 'Pola Camera',
           'advertiser' => '360 Security',
           'imagePath' => './resources/logo/pola_camera.png',
           'accountList' => array(10, 11, 52, 67)
       ),
       3 => array(
           'id' => 3,
           'name' => 'Kwai',
           'advertiser' => '一笑科技',
           'imagePath' => './resources/logo/kwai.png',
           'accountList' => array(10, 11, 52, 67)
       ));

       $response->setData($data);
       return $response;
   }

   public function getAccountInfoByProductId($param)
   {
       $response = new ServiceResult();
       $accountInfo = array();
       $accountInfo['title'] = self::$accountBasicTitle;
       $data = array(
           10 => array(
               "act_001",
               "act_name_001",
               "blue",
               290000,
               289105.3
           ),
           11 => array(
               "act_002",
               "act_name_002",
               "blue",
               3450000,
               569105.3
           ),
           52 => array(
               "act_003",
               "act_name_003",
               "blue",
               2344500,
               12221.3
           ),
           67 => array(
               "act_004",
               "act_name_004",
               "blue",
               290000,
               289105.3
           )
       );

       $accountInfo['datas'] = $data;
       $response->setData($accountInfo);

       return $response;
   }

   public function getAccountCountryBDInsight($param)
   {
       $response = new ServiceResult();
       $accountInfo = array();
       $accountInfo['title'] = self::$accountPerformanceTitle;
       $data = array(
           10 => array(
               array("GH", 56, 24.69, 0.385781),
               array("ID", 64, 24.69, 0.385781),
               array("JP", 89, 24.69, 0.385781),
               array("US", 64, 24.69, 0.385781),
           ),
           11 => array(
               array("GH", 1233, 24.69, 0.385781),
               array("ID", 456, 24.69, 0.385781),
               array("JP", 124, 24.69, 0.385781),
               array("US", 233, 24.69, 0.385781),
           ),
           52 => array(
               array("GH", 1233, 24.69, 0.385781),
               array("ID", 567, 24.69, 0.385781),
               array("JP", 234, 24.69, 0.385781),
               array("US", 3456, 24.69, 0.385781),
           ),
           67 => array(
               array("GH", 1223, 24.69, 0.385781),
               array("ID", 5466, 24.69, 0.385781),
               array("JP", 7689, 24.69, 0.385781),
               array("US", 456, 24.69, 0.385781),
           )
       );

       $accountInfo['datas'] = $data;
       $response->setData($accountInfo);

       return $response;
   }

    private static $accountBasicTitle = array('ID', 'Name', 'Agency', 'Cap', 'Total spend');
    private static $accountPerformanceTitle = array('Country', 'Install', 'Cost', 'CPI');
}