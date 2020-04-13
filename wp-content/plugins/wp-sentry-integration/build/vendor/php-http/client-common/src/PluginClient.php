<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common;

use WPSentry\ScopedVendor\Http\Client\Common\Exception\LoopException;
use WPSentry\ScopedVendor\Http\Client\Exception as HttplugException;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Client\HttpClient;
use WPSentry\ScopedVendor\Http\Client\Promise\HttpFulfilledPromise;
use WPSentry\ScopedVendor\Http\Client\Promise\HttpRejectedPromise;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
use WPSentry\ScopedVendor\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * The client managing plugins and providing a decorator around HTTP Clients.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class PluginClient implements \WPSentry\ScopedVendor\Http\Client\HttpClient, \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient
{
    /**
     * An HTTP async client.
     *
     * @var HttpAsyncClient|HttpClient
     */
    private $client;
    /**
     * The plugin chain.
     *
     * @var Plugin[]
     */
    private $plugins;
    /**
     * A list of options.
     *
     * @var array
     */
    private $options;
    /**
     * @param ClientInterface|HttpAsyncClient $client
     * @param Plugin[]                        $plugins
     * @param array                           $options {
     *
     *     @var int      $max_restarts
     * }
     *
     * @throws \RuntimeException if client is not an instance of HttpClient or HttpAsyncClient
     */
    public function __construct($client, array $plugins = [], array $options = [])
    {
        if ($client instanceof \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient) {
            $this->client = $client;
        } elseif ($client instanceof \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface) {
            $this->client = new \WPSentry\ScopedVendor\Http\Client\Common\EmulatedHttpAsyncClient($client);
        } else {
            throw new \LogicException(\sprintf('Client must be an instance of %s or %s', \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface::class, \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient::class));
        }
        $this->plugins = $plugins;
        $this->options = $this->configure($options);
    }
    /**
     * {@inheritdoc}
     */
    public function sendRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        // If we don't have an http client, use the async call
        if (!$this->client instanceof \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface) {
            return $this->sendAsyncRequest($request)->wait();
        }
        // Else we want to use the synchronous call of the underlying client, and not the async one in the case
        // we have both an async and sync call
        $pluginChain = $this->createPluginChain($this->plugins, function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) {
            try {
                return new \WPSentry\ScopedVendor\Http\Client\Promise\HttpFulfilledPromise($this->client->sendRequest($request));
            } catch (\WPSentry\ScopedVendor\Http\Client\Exception $exception) {
                return new \WPSentry\ScopedVendor\Http\Client\Promise\HttpRejectedPromise($exception);
            }
        });
        return $pluginChain($request)->wait();
    }
    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request)
    {
        $pluginChain = $this->createPluginChain($this->plugins, function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) {
            return $this->client->sendAsyncRequest($request);
        });
        return $pluginChain($request);
    }
    /**
     * Configure the plugin client.
     */
    private function configure(array $options = []) : array
    {
        $resolver = new \WPSentry\ScopedVendor\Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults(['max_restarts' => 10]);
        $resolver->setAllowedTypes('max_restarts', 'int');
        return $resolver->resolve($options);
    }
    /**
     * Create the plugin chain.
     *
     * @param Plugin[] $pluginList     A list of plugins
     * @param callable $clientCallable Callable making the HTTP call
     */
    private function createPluginChain(array $pluginList, callable $clientCallable) : callable
    {
        $firstCallable = $lastCallable = $clientCallable;
        while ($plugin = \array_pop($pluginList)) {
            $lastCallable = function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) use($plugin, $lastCallable, &$firstCallable) {
                return $plugin->handleRequest($request, $lastCallable, $firstCallable);
            };
            $firstCallable = $lastCallable;
        }
        $firstCalls = 0;
        $firstCallable = function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) use($lastCallable, &$firstCalls) {
            if ($firstCalls > $this->options['max_restarts']) {
                throw new \WPSentry\ScopedVendor\Http\Client\Common\Exception\LoopException('Too many restarts in plugin client', $request);
            }
            ++$firstCalls;
            return $lastCallable($request);
        };
        return $firstCallable;
    }
}
