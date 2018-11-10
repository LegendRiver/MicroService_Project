<?php
namespace FBBasicService\Mock;
use FBBasicService\Common\FBServiceResult;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/15
 * Time: 上午9:40
 */
class MockService
{
    public function mockQueryAccountById($param)
    {
        $response = new FBServiceResult();
        $data = array(
            "accountName" => "Test_account001",
            "spendCap" => 70300000,
            "spendAmount" => 66440000,
        );

        $response->setData($data);
        return $response;
    }

    public function mockQueryAccountCountyBDInsight($param)
    {
        $response = new FBServiceResult();
        $data = array(
           'insightData' => array(
               array('ID', '37', '30.68', '0.8291'),
               array('RU', '590', '310.89', '0.8291'),
               array('JP', '370', '40.34', '0.8291'),
               array('US', '680', '6000.78', '0.8291'),
           ),
        );

        $response->setData($data);
        return $response;
    }
}