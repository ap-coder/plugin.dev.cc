<?php

namespace WPSentry\ScopedVendor\Http\Message\StreamFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory;
/**
 * Creates Guzzle streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 */
final class GuzzleStreamFactory implements \WPSentry\ScopedVendor\Http\Message\StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\stream_for($body);
    }
}
