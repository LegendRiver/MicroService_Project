<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 下午3:43
 */

namespace CommonMoudle\Http;


class ResponseInfo
{
    private $body;

    private $header;

    private $stateCode;

    /**
     * @return mixed
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * @param mixed $stateCode
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }
}