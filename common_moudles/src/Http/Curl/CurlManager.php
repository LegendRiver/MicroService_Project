<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 上午11:04
 */

namespace CommonMoudle\Http\Curl;

use CommonMoudle\Http\RequestInfo;
use CommonMoudle\Http\ResponseInfo;

class CurlManager
{
    private $curl;

    private $opts;

    public function __construct()
    {
        $this->curl = $this->createOptimalVersion();
        $this->curl->init();
    }

    private function createOptimalVersion()
    {
        if (version_compare(PHP_VERSION, '5.5.0') >= 0)
        {
            return new HighVersionCurl();
        }
        else
        {
            return new LowerVersionCurl();
        }
    }

    public function getCurl()
    {
        return $this->curl;
    }

    public function getOpts()
    {
        if ($this->opts === null)
        {
            $this->opts = new \ArrayObject(array(
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
            ));
        }

        return $this->opts;
    }

    public function setOpts(\ArrayObject $opts)
    {
        $this->opts = $opts;
    }

    protected function getheaderSize()
    {
        return $this->getCurl()->getInfo(CURLINFO_HEADER_SIZE);
    }

    protected function extractResponseHeadersAndBody($raw_response)
    {
        $header_size = $this->getheaderSize();

        $raw_headers = mb_substr($raw_response, 0, $header_size);
        $raw_body = mb_substr($raw_response, $header_size);

        return array(trim($raw_headers), trim($raw_body));
    }

    protected function parseHeaders($raw_headers)
    {
        $raw_headers = str_replace("\r\n", "\n", $raw_headers);

        // There will be multiple headers if a 301 was followed
        // or a proxy was followed, etc
        $header_collection = explode("\n\n", trim($raw_headers));
        // We just want the last response (at the end)
        $raw_headers = array_pop($header_collection);

        $header_components = explode("\n", $raw_headers);
        $headers = array();
        foreach ($header_components as $line)
        {
            if (strpos($line, ': ') === false)
            {
                $headers['http_code'] = $line;
            }
            else
            {
                list ($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public function sendRequest(RequestInfo $request)
    {
        $this->getCurl()->reset();
        $curlopts = array(
            CURLOPT_URL => $request->getUrl(),
        );

        $method = $request->getMethod();
        if ($method !== RequestInfo::METHOD_GET && $method !== RequestInfo::METHOD_POST)
        {
            $curlopts[CURLOPT_CUSTOMREQUEST] = $method;
        }

        $curlopts = $this->getOpts()->getArrayCopy() + $curlopts;

        if (count($request->getHeaders()))
        {
            $headers = array();
            foreach ($request->getHeaders() as $header => $value) {
                $headers[] = "{$header}: {$value}";
            }
            $curlopts[CURLOPT_HTTPHEADER] = $headers;
        }

        $postfields = array();
        if ($method !== RequestInfo::METHOD_GET && $request->getBodyParam()->count())
        {
            $postfields = array_merge($postfields, $request->getBodyParam()->export());
        }

        if (!empty($postfields))
        {
            $curlopts[CURLOPT_POSTFIELDS] = $postfields;
        }

        $this->getCurl()->setoptArray($curlopts);
        $raw_response = $this->getCurl()->exec();

        $status_code = $this->getCurl()->getInfo(CURLINFO_HTTP_CODE);
        $curl_errno = $this->getCurl()->errno();
        $curl_error = $curl_errno ? $this->getCurl()->error() : null;

        $response_parts = $this->extractResponseHeadersAndBody($raw_response);

        $response = new ResponseInfo();
        $response->setStateCode($status_code);
        $parsedHeader = $this->parseHeaders($response_parts[0]);
        $response->setHeader($parsedHeader);
        $response->setBody($response_parts[1]);

        if ($curl_errno)
        {
            throw new \Exception($curl_error, $curl_errno);
        }

        return $response;
    }
}