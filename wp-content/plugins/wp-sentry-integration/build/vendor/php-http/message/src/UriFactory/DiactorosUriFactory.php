<?php

namespace WPSentry\ScopedVendor\Http\Message\UriFactory;

use WPSentry\ScopedVendor\Http\Message\UriFactory;
use WPSentry\ScopedVendor\Psr\Http\Message\UriInterface;
use WPSentry\ScopedVendor\Zend\Diactoros\Uri;
/**
 * Creates Diactoros URI.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
final class DiactorosUriFactory implements \WPSentry\ScopedVendor\Http\Message\UriFactory
{
    /**
     * {@inheritdoc}
     */
    public function createUri($uri)
    {
        if ($uri instanceof \WPSentry\ScopedVendor\Psr\Http\Message\UriInterface) {
            return $uri;
        } elseif (\is_string($uri)) {
            return new \WPSentry\ScopedVendor\Zend\Diactoros\Uri($uri);
        }
        throw new \InvalidArgumentException('URI must be a string or UriInterface');
    }
}
