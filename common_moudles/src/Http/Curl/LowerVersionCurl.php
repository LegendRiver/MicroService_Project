<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 上午11:01
 */

namespace CommonMoudle\Http\Curl;


class LowerVersionCurl extends AbstractCurl
{
    public function __construct()
    {
        parent::__construct();
        if (version_compare(PHP_VERSION, '5.5.0') >= 0)
        {
            throw new \RuntimeException("Unsupported Curl version");
        }
    }

    public function escape($string)
    {
        return rawurlencode($string);
    }

    public function pause($bitmask)
    {
        return 0;
    }

    public function reset()
    {
        $this->handle && curl_close($this->handle);
        $this->handle = curl_init();
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