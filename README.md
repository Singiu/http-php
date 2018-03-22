# PHP发送HTTP请求及处理响应的轻量组件

[![Latest Stable Version](https://poser.pugx.org/singiu/http-php/v/stable)](https://packagist.org/packages/singiu/http-php)
[![Total Downloads](https://poser.pugx.org/singiu/http-php/downloads)](https://packagist.org/packages/singiu/http-php)
[![Latest Unstable Version](https://poser.pugx.org/singiu/http-php/v/unstable)](https://packagist.org/packages/singiu/http-php)
[![License](https://poser.pugx.org/singiu/http-php/license)](https://packagist.org/packages/singiu/http-php)
[![composer.lock](https://poser.pugx.org/singiu/http-php/composerlock)](https://packagist.org/packages/singiu/http-php)

这个是用在我自己开发的其它组件包中的一个包，因为多个组件都有用到，所以我把它提取了出来，做成一个独立的包。
源码只是对 php-curl 模块方法的简单封装，功能非常轻量，如果你觉得合适，欢迎拿去使用。如果有使用上的问题或者 Bug，也欢迎提交到 Issues 中来。

现已支持 RESTful 请求方法。

## 安装

可以通过 Composer 安装：

```shell
composer require singiu/http-php
```

## 基本用法

### 发送请求

#### 发送 GET 请求。

```php

use Singiu\Http\Http;

Http::setBaseUrl('http://localhost');

$response = Http::get('/api/user');

$response = Http::get('api/user', [ // 最开头的 "/" 不要也可以。
    // 发送请求参数（当然你也可以自己拼接在 URL 的尾部）：
    'query' => [
        'page' => 2
    ]
]);

$result = $response->getResponseObject();
echo $result->username;
```

#### 发送 POST 请求。

```php

use Singiu\Http\Http;

Http::setBaseUrl('http://localhost');

$response = Http:post('/api/user', [
    // 发送数据，这里和 GET 方法有些不一样，因为考虑到有些请求需要 GET 和 POST 同时传参的情况。
    'data' => [
        'username' => 'singiu',
        'phone' => 13838389438
    ]
]);

echo $response->getResponseText();
```

#### 其它参数：headers、timeout
```php
use Singiu\Http\Http;

$response = Http:get('/api/user', [
    // timeout 设置请求连接等待超时时间，单位为秒；headers 可以设置请求头信息。
    'timeout' => 30,
    'headers' => [
        'Content-Type' => 'application/json'
    ]
]);

$result = $response->getResponseArray();
$result['username'];
```

#### 发送REST请求方法。
PUT、DELETE、OPTIONS、HEAD、PATCH 这类请求方法与 GET 类似。
需要注意的是，所有的方法都可以传入 'data' 参数来设定要传输的数据，但是除了 POST 请求可以在服务端使用 `$_POST` 全局变量来获取外，其它请求方法都不能正常获取，只能通过 `get_file_content('php://input')` 来获取并且需要用户自己解析。

### 处理响应
Singiu\Http\Http::post 方法 和 Singiu\Http\Http::get 方法返回的是一个 Singiu\Http\Response 类的实例对象。
它现在包含以下方法：
```php
$response->getUrl();            // 获取该响应的请求 URL。
$response->getStatusCode();     // 获取该响应的 HTTP 响应码。
$response->getResponseText();   // 以文本格式获取响应的内容。
$response->getResponseArray();  // 如果返回的文本是正确的 JSON 格式，则会返回一个解析后的数组。
$response->getResponseObject(); // 如果返回的文本是正确的 JSON 格式，则会返回一个解析后的对象。
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
