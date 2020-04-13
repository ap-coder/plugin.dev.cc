<?php

namespace WPSentry\ScopedVendor\Http\Factory\Guzzle;

use WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface;
class StreamFactory implements \WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface
{
    public function createStream(string $content = '') : \WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface
    {
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\stream_for($content);
    }
    public function createStreamFromFile(string $file, string $mode = 'r') : \WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface
    {
        $resource = \WPSentry\ScopedVendor\GuzzleHttp\Psr7\try_fopen($file, $mode);
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\stream_for($resource);
    }
    public function createStreamFromResource($resource) : \WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface
    {
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\stream_for($resource);
    }
}
