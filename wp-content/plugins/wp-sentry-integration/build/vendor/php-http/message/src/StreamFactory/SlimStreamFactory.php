<?php

namespace WPSentry\ScopedVendor\Http\Message\StreamFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface;
use WPSentry\ScopedVendor\Slim\Http\Stream;
/**
 * Creates Slim 3 streams.
 *
 * @author Mika Tuupola <tuupola@appelsiini.net>
 */
final class SlimStreamFactory implements \WPSentry\ScopedVendor\Http\Message\StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if ($body instanceof \WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface) {
            return $body;
        }
        if (\is_resource($body)) {
            return new \WPSentry\ScopedVendor\Slim\Http\Stream($body);
        }
        $resource = \fopen('php://memory', 'r+');
        $stream = new \WPSentry\ScopedVendor\Slim\Http\Stream($resource);
        if (null !== $body && '' !== $body) {
            $stream->write((string) $body);
        }
        return $stream;
    }
}
