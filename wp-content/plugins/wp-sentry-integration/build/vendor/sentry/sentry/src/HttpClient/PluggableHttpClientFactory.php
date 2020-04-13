<?php

declare (strict_types=1);
namespace Sentry\HttpClient;

use WPSentry\ScopedVendor\Http\Client\Common\Plugin as HttpClientPluginInterface;
use WPSentry\ScopedVendor\Http\Client\Common\PluginClient;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient as HttpAsyncClientInterface;
use Sentry\Options;
/**
 * This factory can be used to decorate an HTTP client with a list of plugins.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 *
 * @deprecated since version 2.3, to be removed in 3.0
 */
final class PluggableHttpClientFactory implements \Sentry\HttpClient\HttpClientFactoryInterface
{
    /**
     * @var HttpClientFactoryInterface The HTTP factory being decorated
     */
    private $decoratedHttpClientFactory;
    /**
     * @var HttpClientPluginInterface[] The list of plugins to add to the HTTP client
     */
    private $httpClientPlugins;
    /**
     * Constructor.
     *
     * @param HttpClientFactoryInterface  $decoratedHttpClientFactory The HTTP factory being decorated
     * @param HttpClientPluginInterface[] $httpClientPlugins          The list of plugins to add to the HTTP client
     */
    public function __construct(\Sentry\HttpClient\HttpClientFactoryInterface $decoratedHttpClientFactory, array $httpClientPlugins)
    {
        $this->decoratedHttpClientFactory = $decoratedHttpClientFactory;
        $this->httpClientPlugins = $httpClientPlugins;
    }
    /**
     * {@inheritdoc}
     */
    public function create(\Sentry\Options $options) : \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient
    {
        $httpClient = $this->decoratedHttpClientFactory->create($options);
        return new \WPSentry\ScopedVendor\Http\Client\Common\PluginClient($httpClient, $this->httpClientPlugins);
    }
}
