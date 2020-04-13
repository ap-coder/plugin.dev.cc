<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common;

use WPSentry\ScopedVendor\Http\Message\RequestFactory;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
final class HttpMethodsClient implements \WPSentry\ScopedVendor\Http\Client\Common\HttpMethodsClientInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var RequestFactory
     */
    private $requestFactory;
    public function __construct(\WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface $httpClient, \WPSentry\ScopedVendor\Http\Message\RequestFactory $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }
    public function get($uri, array $headers = []) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('GET', $uri, $headers, null);
    }
    public function head($uri, array $headers = []) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('HEAD', $uri, $headers, null);
    }
    public function trace($uri, array $headers = []) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('TRACE', $uri, $headers, null);
    }
    public function post($uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('POST', $uri, $headers, $body);
    }
    public function put($uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('PUT', $uri, $headers, $body);
    }
    public function patch($uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('PATCH', $uri, $headers, $body);
    }
    public function delete($uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('DELETE', $uri, $headers, $body);
    }
    public function options($uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->send('OPTIONS', $uri, $headers, $body);
    }
    public function send(string $method, $uri, array $headers = [], $body = null) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest($method, $uri, $headers, $body));
    }
    public function sendRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
