<?php

namespace WPSentry\ScopedVendor\Http\Message\UriFactory;

use WPSentry\ScopedVendor\GuzzleHttp\Psr7;
use WPSentry\ScopedVendor\Http\Message\UriFactory;
/**
 * Creates Guzzle URI.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
final class GuzzleUriFactory implements \WPSentry\ScopedVendor\Http\Message\UriFactory
{
    /**
     * {@inheritdoc}
     */
    public function createUri($uri)
    {
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\uri_for($uri);
    }
}
