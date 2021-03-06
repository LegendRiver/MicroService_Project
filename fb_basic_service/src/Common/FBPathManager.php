<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/21
 * Time: 下午8:05
 */

namespace FBBasicService\Common;

use FBBasicService\Constant\FBDirConstant;

class FBPathManager
{
    private $srcPath;

    private $confPath;

    private static $instance = null;

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->srcPath = dirname(__DIR__);
        $this->confPath = $this->srcPath . DIRECTORY_SEPARATOR . FBDirConstant::DIR_CONF;
    }

    public function getLogConfigPath()
    {
        return $this->confPath . DIRECTORY_SEPARATOR . FBDirConstant::LOGGER_CONF_FILE;
    }

    public function getServiceConfigPath()
    {
        return $this->confPath . DIRECTORY_SEPARATOR . FBDirConstant::SERVICE_CONF_FILE;
    }

    /**
     * @return string
     */
    public function getSrcPath()
    {
        return $this->srcPath;
    }

    /**
     * @return string
     */
    public function getConfPath()
    {
        return $this->confPath;
    }

}