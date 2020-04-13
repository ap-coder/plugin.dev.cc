<?php

namespace WPSentry\ScopedVendor\Http\Message\MessageFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory\SlimStreamFactory;
use WPSentry\ScopedVendor\Http\Message\UriFactory\SlimUriFactory;
use WPSentry\ScopedVendor\Http\Message\MessageFactory;
use WPSentry\ScopedVendor\Slim\Http\Request;
use WPSentry\ScopedVendor\Slim\Http\Response;
use WPSentry\ScopedVendor\Slim\Http\Headers;
/**
 * Creates Slim 3 messages.
 *
 * @author Mika Tuupola <tuupola@appelsiini.net>
 */
final class SlimMessageFactory implements \WPSentry\ScopedVendor\Http\Message\MessageFactory
{
    /**
     * @var SlimStreamFactory
     */
    private $streamFactory;
    /**
     * @var SlimUriFactory
     */
    private $uriFactory;
    public function __construct()
    {
        $this->streamFactory = new \WPSentry\ScopedVendor\Http\Message\StreamFactory\SlimStreamFactory();
        $this->uriFactory = new \WPSentry\ScopedVendor\Http\Message\UriFactory\SlimUriFactory();
    }
    /**
     * {@inheritdoc}
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return (new \WPSentry\ScopedVendor\Slim\Http\Request($method, $this->uriFactory->createUri($uri), new \WPSentry\ScopedVendor\Slim\Http\Headers($headers), [], [], $this->streamFactory->createStream($body), []))->withProtocolVersion($protocolVersion);
    }
    /**
     * {@inheritdoc}
     */
    public function createResponse($statusCode = 200, $reasonPhrase = null, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return (new \WPSentry\ScopedVendor\Slim\Http\Response($statusCode, new \WPSentry\ScopedVendor\Slim\Http\Headers($headers), $this->streamFactory->createStream($body)))->withProtocolVersion($protocolVersion);
    }
}
