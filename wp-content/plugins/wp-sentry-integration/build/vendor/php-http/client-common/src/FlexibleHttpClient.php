<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common;

use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Client\HttpClient;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
/**
 * A flexible http client, which implements both interface and will emulate
 * one contract, the other, or none at all depending on the injected client contract.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class FlexibleHttpClient implements \WPSentry\ScopedVendor\Http\Client\HttpClient, \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient
{
    use HttpClientDecorator;
    use HttpAsyncClientDecorator;
    /**
     * @param ClientInterface|HttpAsyncClient $client
     */
    public function __construct($client)
    {
        if (!$client instanceof \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface && !$client instanceof \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient) {
            throw new \LogicException(\sprintf('Client must be an instance of %s or %s', \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface::class, \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient::class));
        }
        $this->httpClient = $client;
        $this->httpAsyncClient = $client;
        if (!$this->httpClient instanceof \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface) {
            $this->httpClient = new \WPSentry\ScopedVendor\Http\Client\Common\EmulatedHttpClient($this->httpClient);
        }
        if (!$this->httpAsyncClient instanceof \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient) {
            $this->httpAsyncClient = new \WPSentry\ScopedVendor\Http\Client\Common\EmulatedHttpAsyncClient($this->httpAsyncClient);
        }
    }
}
