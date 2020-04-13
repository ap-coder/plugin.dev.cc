<?php

declare (strict_types=1);
namespace Sentry\HttpClient\Plugin;

use WPSentry\ScopedVendor\Http\Client\Common\Plugin as PluginInterface;
use WPSentry\ScopedVendor\Http\Discovery\StreamFactoryDiscovery;
use WPSentry\ScopedVendor\Http\Message\StreamFactory as StreamFactoryInterface;
use WPSentry\ScopedVendor\Http\Promise\Promise as PromiseInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
/**
 * This plugin encodes the request body by compressing it with Gzip.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class GzipEncoderPlugin implements \WPSentry\ScopedVendor\Http\Client\Common\Plugin
{
    /**
     * @var StreamFactoryInterface The PSR-17 stream factory
     */
    private $streamFactory;
    /**
     * Constructor.
     *
     * @param StreamFactoryInterface|null $streamFactory The stream factory
     *
     * @throws \RuntimeException If the zlib extension is not enabled
     */
    public function __construct(?\WPSentry\ScopedVendor\Http\Message\StreamFactory $streamFactory = null)
    {
        if (!\extension_loaded('zlib')) {
            throw new \RuntimeException('The "zlib" extension must be enabled to use this plugin.');
        }
        if (null === $streamFactory) {
            @\trigger_error(\sprintf('A PSR-17 stream factory is needed as argument of the constructor of the "%s" class since version 2.1.3 and will be required in 3.0.', self::class), \E_USER_DEPRECATED);
        }
        $this->streamFactory = $streamFactory ?? \WPSentry\ScopedVendor\Http\Discovery\StreamFactoryDiscovery::find();
    }
    /**
     * {@inheritdoc}
     */
    public function handleRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, callable $next, callable $first) : \WPSentry\ScopedVendor\Http\Promise\Promise
    {
        $requestBody = $request->getBody();
        if ($requestBody->isSeekable()) {
            $requestBody->rewind();
        }
        $encodedBody = \gzcompress($requestBody->getContents(), -1, \ZLIB_ENCODING_GZIP);
        if (\false === $encodedBody) {
            throw new \RuntimeException('Failed to GZIP-encode the request body.');
        }
        $request = $request->withHeader('Content-Encoding', 'gzip');
        $request = $request->withBody($this->streamFactory->createStream($encodedBody));
        return $next($request);
    }
}
