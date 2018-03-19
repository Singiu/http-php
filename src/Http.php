<?php

namespace Singiu\Http;

/**
 * @method static Response get(string $url, array $options = null)
 * @method static Response put(string $url, array $options = null)
 * @method static Response post(string $url, array $options = null)
 * @method static Response head(string $url, array $options = null)
 * @method static Response patch(string $url, array $options = null)
 * @method static Response delete(string $url, array $options = null)
 * @method static Response options(string $url, array $options = null)
 */
class Http
{
    const HTTP_VERSION_1_0 = 1;
    const HTTP_VERSION_1_1 = 2;
    private static $request;
    private static $allow_methods = array(
        'get', 'post', 'put', 'delete', 'patch', 'options', 'head'
    );

    public static function __callStatic($name, $arguments)
    {
        if (self::$request == null)
            self::$request = new Request();
        if (in_array($name, self::$allow_methods) && method_exists(self::$request, $name)) {
            $response = call_user_func_array(array(self::$request, $name), $arguments);
            return $response;
        } else {
            throw new \Exception('找不到静态方法：' . $name);
        }
    }
}