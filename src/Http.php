<?php

namespace Singiu\Http;

/**
 * @method static Response post($url, $options)
 * @method static Response get($url, $options = null)
 */
class Http
{
    private static $baseUrl;

    public static function __callStatic($name, $arguments)
    {
        $request = new Request();
        if (self::$baseUrl) $request->setBaseUrl(self::$baseUrl);
        if (method_exists($request, $name)) {
            $response = call_user_func_array(array($request, $name), $arguments);
            return $response;
        } else {
            throw new \Exception('找不到静态方法：' . $name);
        }
    }

    public static function setBaseUrl($url)
    {
        self::$baseUrl = $url;
    }
}