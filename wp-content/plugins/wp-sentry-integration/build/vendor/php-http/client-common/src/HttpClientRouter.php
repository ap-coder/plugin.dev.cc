<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common;

use WPSentry\ScopedVendor\Http\Client\Common\Exception\HttpClientNoMatchException;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Message\RequestMatcher;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
/**
 * {@inheritdoc}
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class HttpClientRouter implements \WPSentry\ScopedVendor\Http\Client\Common\HttpClientRouterInterface
{
    /**
     * @var array
     */
    private $clients = [];
    /**
     * {@inheritdoc}
     */
    public function sendRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->chooseHttpClient($request)->sendRequest($request);
    }
    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request)
    {
        return $this->chooseHttpClient($request)->sendAsyncRequest($request);
    }
    /**
     * Add a client to the router.
     *
     * @param ClientInterface|HttpAsyncClient $client
     */
    public function addClient($client, \WPSentry\ScopedVendor\Http\Message\RequestMatcher $requestMatcher) : void
    {
        $this->clients[] = ['matcher' => $requestMatcher, 'client' => new \WPSentry\ScopedVendor\Http\Client\Common\FlexibleHttpClient($client)];
    }
    /**
     * Choose an HTTP client given a specific request.
     *
     * @return ClientInterface|HttpAsyncClient
     */
    private function chooseHttpClient(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request)
    {
        foreach ($this->clients as $client) {
            if ($client['matcher']->matches($request)) {
                return $client['client'];
            }
        }
        throw new \WPSentry\ScopedVendor\Http\Client\Common\Exception\HttpClientNoMatchException('No client found for the specified request', $request);
    }
}
