<?php

namespace Singiu\Http;

class Request
{
    private $curl;
    private $base_uri;

    /**
     * 构造函数。
     * @param array $options
     */
    public function __construct($options = null)
    {
        $this->curl = curl_init();
        if (is_array($options) && array_key_exists('base_uri', $options) && is_string($options['base_uri'])) {
            $this->base_uri = $options['base_uri'];
        }
    }

    /**
     * POST 请求方法。
     * @param string $url
     * @param array $options
     * @return Response
     */
    public function post($url, $options = [])
    {
        $this->setUrl($url);
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
        $this->setUrl($url);
        curl_setopt($this->curl, CURLOPT_POST, false);
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
                case 'query':
                    if (is_array($item)) {
                        $url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
                        $url .= '?' . http_build_query($options['query']);
                        curl_setopt($curl, CURLOPT_URL, $url);
                    }
                    break;
                case 'timeout':
                    if (is_int($item)) {
                        curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);
                    }
                    break;
                case 'headers':
                    if (is_array($item)) {
                        $header = [];
                        foreach ($options['headers'] as $k => $v) {
                            array_push($header, $k . ': ' . $v);
                        }
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    }
                    break;
                case 'data':
                    if (is_array($item)) {
                        $fields = http_build_query($options['data']);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
                    }
            }
        }
    }
}