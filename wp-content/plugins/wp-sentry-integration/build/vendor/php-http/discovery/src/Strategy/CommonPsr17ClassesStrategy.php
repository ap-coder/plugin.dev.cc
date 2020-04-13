<?php

namespace WPSentry\ScopedVendor\Http\Discovery\Strategy;

use WPSentry\ScopedVendor\Psr\Http\Message\RequestFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface;
/**
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class CommonPsr17ClassesStrategy implements \WPSentry\ScopedVendor\Http\Discovery\Strategy\DiscoveryStrategy
{
    /**
     * @var array
     */
    private static $classes = [\WPSentry\ScopedVendor\Psr\Http\Message\RequestFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\RequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\RequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\RequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\RequestFactory'], \WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\ResponseFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\ResponseFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\ResponseFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\ResponseFactory'], \WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\ServerRequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\ServerRequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\ServerRequestFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\ServerRequestFactory'], \WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\StreamFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\StreamFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\StreamFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\StreamFactory'], \WPSentry\ScopedVendor\Psr\Http\Message\UploadedFileFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\UploadedFileFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\UploadedFileFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\UploadedFileFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\UploadedFileFactory'], \WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface::class => ['WPSentry\\ScopedVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'WPSentry\\ScopedVendor\\Zend\\Diactoros\\UriFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Diactoros\\UriFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Guzzle\\UriFactory', 'WPSentry\\ScopedVendor\\Http\\Factory\\Slim\\UriFactory']];
    /**
     * {@inheritdoc}
     */
    public static function getCandidates($type)
    {
        $candidates = [];
        if (isset(self::$classes[$type])) {
            foreach (self::$classes[$type] as $class) {
                $candidates[] = ['class' => $class, 'condition' => [$class]];
            }
        }
        return $candidates;
    }
}
