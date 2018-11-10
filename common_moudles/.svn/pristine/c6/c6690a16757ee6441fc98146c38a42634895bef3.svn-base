<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 上午11:02
 */

namespace CommonMoudle\Http\Curl;


class HighVersionCurl extends AbstractCurl
{
    public function __construct()
    {
        parent::__construct();
        if (version_compare(PHP_VERSION, '5.5.0') < 0)
        {
            throw new \RuntimeException("Unsupported Curl version");
        }
    }

    public function escape($string)
    {
        return curl_escape($this->handle, $string);
    }

    public function pause($bitmask)
    {
        return curl_pause($this->handle, $bitmask);
    }

    public function reset()
    {
        $this->handle && curl_reset($this->handle);
    }

    public static function strerror($errornum)
    {
        return curl_strerror($errornum);
    }

    public function unescape($string)
    {
        return curl_unescape($this->handle, $string);
    }
}