# PHP发送HTTP请求及处理响应的轻量组件

[![Latest Stable Version](https://poser.pugx.org/singiu/http-php/v/stable)](https://packagist.org/packages/singiu/http-php)
[![Total Downloads](https://poser.pugx.org/singiu/http-php/downloads)](https://packagist.org/packages/singiu/http-php)
[![Latest Unstable Version](https://poser.pugx.org/singiu/http-php/v/unstable)](https://packagist.org/packages/singiu/http-php)
[![License](https://poser.pugx.org/singiu/http-php/license)](https://packagist.org/packages/singiu/http-php)
[![composer.lock](https://poser.pugx.org/singiu/http-php/composerlock)](https://packagist.org/packages/singiu/http-php)

这个是用在我自己开发的其它组件包中的一个包，因为多个组件都有用到，所以我把它提取了出来，做成一个独立的包。
源码只是对php-curl模块方法的简单封装，功能非常轻量，如果你觉得合适，欢迎拿去使用。如果有使用上的问题或者Bug，也欢迎提交到 Issues 中来。

此包还会持续更新，比如加入 PUT、DELETE 请求的对应使用方法。

## 安装

可以通过 Composer 安装：

```shell
composer require singiu/http-php
```

## 基本用法

### 发送请求

```php
// 发送 GET 请求。
use Singiu\Http\Http;

Http::setBaseUrl('http://localhost');

$response = Http::get('/api/user');

$response = Http::get('api/user', [ // 最开头的 "/" 不要也可以。
    // 发送请求参数（当然你也可以自己拼接在 URL 的尾部）：
    'query' => [
        'page' => 2
    ]
]);

echo $response->getResponseText();
```

```php
// 发送 POST 请求。
use Singiu\Http\Http;

Http::setBaseUrl('http://localhost');

$response = Http:post('/api/user', [
    // 发送数据，这里和 GET 方法有些不一样。
    'data' => [
        'username' => 'singiu',
        'phone' => 13838389438
    ]
]);
```

### 处理回应
Singiu\Http\Http::post 方法 和 Singiu\Http\Http::get 方法返回的是一个 Singiu\Http\Response 类的实例对象。
它现在包含以下方法：
```php
$response->getUrl();          // 获取该响应的请求 URL。
$response->getStatusCode();   // 获取该响应的 HTTP 响应码。
$response->getResponseText(); // 以文本格式获取响应的内容。
$response->getResponseJson(); // 以 JSON 格式获取响应的内容，如果内容转换失败会返回 null。
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
