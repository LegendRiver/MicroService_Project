<?php
namespace CommonMoudle\Http;
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 下午3:43
 */
class RequestInfo
{
    const METHOD_DELETE = 'DELETE';

    const METHOD_GET = 'GET';

    const METHOD_POST = 'POST';

    const METHOD_PUT = 'PUT';

    const PROTOCOL_HTTP = 'http://';

    const PROTOCOL_HTTPS = 'https://';

    private $protocol;

    private $server;

    private $port;

    private $endpoint;

    private $queryParam;

    private $bodyParam;

    private $method;

    private $headers;

    public function __construct()
    {
        $this->protocol = self::PROTOCOL_HTTP;
        $this->queryParam = new RequestParameter();
        $this->headers = array();
    }

    public function getUrl()
    {
        $serverInfo = $this->protocol . $this->server . ':' .$this->port . '/';
        $endpoint = $this->endpoint . ($this->queryParam->count() ? '?': null);
        $params = http_build_query($this->queryParam->export());

        return $serverInfo . $endpoint . $params;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return RequestParameter
     */
    public function getQueryParam()
    {
        if ($this->queryParam === null)
        {
            $this->queryParam = new RequestParameter();
        }

        return $this->queryParam;
    }

    /**
     * @param RequestParameter $queryParam
     */
    public function setQueryParam($queryParam)
    {
        $this->queryParam = $queryParam;
    }

    /**
     * @return mixed
     */
    public function getBodyParam()
    {
        if ($this->bodyParam === null)
        {
            $this->bodyParam = new RequestParameter();
        }

        return $this->bodyParam;
    }

    /**
     * @param mixed $bodyParam
     */
    public function setBodyParam($bodyParam)
    {
        $this->bodyParam = $bodyParam;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }



}