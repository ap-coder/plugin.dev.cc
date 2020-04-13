<?php

namespace WPSentry\ScopedVendor\Http\Message\StreamFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface;
use WPSentry\ScopedVendor\Zend\Diactoros\Stream;
/**
 * Creates Diactoros streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 */
final class DiactorosStreamFactory implements \WPSentry\ScopedVendor\Http\Message\StreamFactory
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
            return new \WPSentry\ScopedVendor\Zend\Diactoros\Stream($body);
        }
        $stream = new \WPSentry\ScopedVendor\Zend\Diactoros\Stream('php://memory', 'rw');
        if (null !== $body && '' !== $body) {
            $stream->write((string) $body);
        }
        return $stream;
    }
}
