<?php

namespace Singiu\Http;

class Request
{
    private $curl;
    private $base_uri;
    private $http_version;

    /**
     * 构造函数。
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        if (is_string($baseUrl))
            $this->base_uri = $baseUrl;
    }

    /**
     * POST 请求方法。
     * @param string $url
     * @param array $options
     * @return Response
     */
    public function post($url, $options = [])
    {
        $this->curl = curl_init();
        $this->setUrl($url);
        $this->setHttpVersion($this->http_version);
        curl_setopt($this->curl, CURLOPT_POST, true);
        if (is_array($options) && !empty($options))
            self::setOptions($this->curl, $options);
        return new Response($this->curl);
    }

    /**
     * GET 请求方法。
     * @param $url
     * @param array $options
     * @return Response
     */
    public function get($url, $options = [])
    {
        $this->curl = curl_init();
        $this->setUrl($url);
        $this->setHttpVersionOption($this->http_version);
        $this->setHttpMethodOption('get');
        if (is_array($options) && !empty($options))
            self::setOptions($this->curl, $options);
        return new Response($this->curl);
    }

    /**
     * 设置 BaseUrl 参数。
     * @param string $url
     */
    public function setBaseUrl($url)
    {
        if (is_string($url))
            $this->base_uri = $url;
    }

    /**
     * 设置 Http Version 参数。
     * @param $httpVersion
     */
    public function setHttpVersion($httpVersion)
    {
        if (is_int($httpVersion))
            $this->http_version = $httpVersion;
    }

    /**
     * 设置 http 协议版本。
     * @param $httpVersion
     */
    private function setHttpVersionOption($httpVersion)
    {
        switch ($httpVersion) {
            case Http::HTTP_VERSION_1_0:
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                break;
            case Http::HTTP_VERSION_1_1:
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                break;
            default:
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_NONE);
        }
    }

    private function setHttpMethodOption($method)
    {
        $method = strtoupper($method);
        switch ($method) {
            case 'GET':
                curl_setopt($this->curl, CURLOPT_POST, false);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST, true);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'PUT':
                curl_setopt($this->curl, CURLOPT_POST, false);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'DELETE':
                curl_setopt($this->curl, CURLOPT_POST, false);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
    }

    /**
     * 给请求设置 url 参数。
     * @param string $url
     */
    private function setUrl($url)
    {
        // 如果 $url 以 http(s):// 开头，则无视 baseUrl。
        if (!preg_match('/^https?:\/\//', $url) && $this->base_uri != null && $this->base_uri != '')
            $url = rtrim(trim($this->base_uri), '/') . '/' . ltrim(trim($url), '/');
        curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    /**
     * 设置参数。
     * @param $curl
     * @param array $options
     */
    private function setOptions(&$curl, $options)
    {
        foreach ($options as $key => $item) {
            switch ($key) {
                case 'timeout':
                    if (is_int($item)) {
                        curl_setopt($curl, CURLOPT_TIMEOUT, $item);
                    }
                    break;
                case 'headers':
                    if (is_array($item)) {
                        $header = [];
                        foreach ($item as $k => $v) {
                            array_push($header, $k . ': ' . $v);
                        }
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    }
                    break;
                case 'query':
                    if (is_array($item)) {
                        $url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
                        $url .= '?' . http_build_query($options['query']);
                        curl_setopt($curl, CURLOPT_URL, $url);
                    }
                    break;
                case 'data':
                    if (is_array($item)) {
                        $fields = http_build_query($item);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
                    }
            }
        }
    }
}