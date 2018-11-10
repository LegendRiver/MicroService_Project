<?php
namespace DBService\Manager\OrionDB;
use CommonMoudle\DBManager\BaseDBManager;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/9
 * Time: 下午2:56
 */
class OrionDBConnection
{
    private static $instance = null;

    private $dbManager;

    private function __construct()
    {
        $dbConf = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'conf/orion_db_conf.json';
        $this->dbManager = new BaseDBManager($dbConf);
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function getOrionDBManager()
    {
        return $this->dbManager;
    }
}