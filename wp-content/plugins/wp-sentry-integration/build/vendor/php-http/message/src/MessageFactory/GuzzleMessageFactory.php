<?php

namespace WPSentry\ScopedVendor\Http\Message\MessageFactory;

use WPSentry\ScopedVendor\GuzzleHttp\Psr7\Request;
use WPSentry\ScopedVendor\GuzzleHttp\Psr7\Response;
use WPSentry\ScopedVendor\Http\Message\MessageFactory;
/**
 * Creates Guzzle messages.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class GuzzleMessageFactory implements \WPSentry\ScopedVendor\Http\Message\MessageFactory
{
    /**
     * {@inheritdoc}
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return new \WPSentry\ScopedVendor\GuzzleHttp\Psr7\Request($method, $uri, $headers, $body, $protocolVersion);
    }
    /**
     * {@inheritdoc}
     */
    public function createResponse($statusCode = 200, $reasonPhrase = null, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return new \WPSentry\ScopedVendor\GuzzleHttp\Psr7\Response($statusCode, $headers, $body, $protocolVersion, $reasonPhrase);
    }
}