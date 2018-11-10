<?php
namespace CommonMoudle\Http\Curl;

/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 上午10:27
 */
abstract class AbstractCurl
{
    protected $handle;

    public function __construct()
    {
        if (!extension_loaded('curl'))
        {
            throw new \RuntimeException("Extension curl not loaded");
        }
    }

    public function __clone()
    {
        $this->handle = curl_copy_handle($this->handle);
    }

    public function __destruct()
    {
        if ($this->handle !== null)
        {
            curl_close($this->handle);
        }
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function errno()
    {
        return curl_errno($this->handle);
    }

    public function error()
    {
        return curl_error($this->handle);
    }

    public function exec()
    {
        return curl_exec($this->handle);
    }

    public function getInfo($opt = 0)
    {
        return curl_getinfo($this->handle, $opt);
    }

    public function init()
    {
        $this->handle = $this->handle ?: curl_init();
    }

    public function setoptArray(array $opts)
    {
        curl_setopt_array($this->handle, $opts);
    }

    public function setopt($option, $value)
    {
        return curl_setopt($this->handle, $option, $value);
    }

    public static function version($age)
    {
        return curl_version($age);
    }
}