<?php

namespace WPSentry\ScopedVendor\Http\Message\MessageFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory\DiactorosStreamFactory;
use WPSentry\ScopedVendor\Http\Message\MessageFactory;
use WPSentry\ScopedVendor\Zend\Diactoros\Request;
use WPSentry\ScopedVendor\Zend\Diactoros\Response;
/**
 * Creates Diactoros messages.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
final class DiactorosMessageFactory implements \WPSentry\ScopedVendor\Http\Message\MessageFactory
{
    /**
     * @var DiactorosStreamFactory
     */
    private $streamFactory;
    public function __construct()
    {
        $this->streamFactory = new \WPSentry\ScopedVendor\Http\Message\StreamFactory\DiactorosStreamFactory();
    }
    /**
     * {@inheritdoc}
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return (new \WPSentry\ScopedVendor\Zend\Diactoros\Request($uri, $method, $this->streamFactory->createStream($body), $headers))->withProtocolVersion($protocolVersion);
    }
    /**
     * {@inheritdoc}
     */
    public function createResponse($statusCode = 200, $reasonPhrase = null, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return (new \WPSentry\ScopedVendor\Zend\Diactoros\Response($this->streamFactory->createStream($body), $statusCode, $headers))->withProtocolVersion($protocolVersion);
    }
}
