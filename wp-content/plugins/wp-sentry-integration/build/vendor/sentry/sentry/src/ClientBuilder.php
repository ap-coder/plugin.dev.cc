<?php

declare (strict_types=1);
namespace Sentry;

use WPSentry\ScopedVendor\Http\Client\Common\Plugin as PluginInterface;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Discovery\MessageFactoryDiscovery;
use WPSentry\ScopedVendor\Http\Discovery\StreamFactoryDiscovery;
use WPSentry\ScopedVendor\Http\Discovery\UriFactoryDiscovery;
use WPSentry\ScopedVendor\Http\Message\MessageFactory as MessageFactoryInterface;
use WPSentry\ScopedVendor\Http\Message\StreamFactory as StreamFactoryInterface;
use WPSentry\ScopedVendor\Http\Message\UriFactory as UriFactoryInterface;
use WPSentry\ScopedVendor\Jean85\PrettyVersions;
use Sentry\HttpClient\HttpClientFactory;
use Sentry\HttpClient\PluggableHttpClientFactory;
use Sentry\Serializer\RepresentationSerializer;
use Sentry\Serializer\RepresentationSerializerInterface;
use Sentry\Serializer\Serializer;
use Sentry\Serializer\SerializerInterface;
use Sentry\Transport\DefaultTransportFactory;
use Sentry\Transport\TransportFactoryInterface;
use Sentry\Transport\TransportInterface;
/**
 * The default implementation of {@link ClientBuilderInterface}.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class ClientBuilder implements \Sentry\ClientBuilderInterface
{
    /**
     * @var Options The client options
     */
    private $options;
    /**
     * @var UriFactoryInterface|null The PSR-7 URI factory
     */
    private $uriFactory;
    /**
     * @var StreamFactoryInterface|null The PSR-17 stream factory
     */
    private $streamFactory;
    /**
     * @var MessageFactoryInterface|null The PSR-7 message factory
     */
    private $messageFactory;
    /**
     * @var TransportFactoryInterface|null The transport factory
     */
    private $transportFactory;
    /**
     * @var TransportInterface|null The transport
     */
    private $transport;
    /**
     * @var HttpAsyncClient|null The HTTP client
     */
    private $httpClient;
    /**
     * @var PluginInterface[] The list of Httplug plugins
     */
    private $httpClientPlugins = [];
    /**
     * @var SerializerInterface|null The serializer to be injected in the client
     */
    private $serializer;
    /**
     * @var RepresentationSerializerInterface|null The representation serializer to be injected in the client
     */
    private $representationSerializer;
    /**
     * @var string The SDK identifier, to be used in {@see Event} and {@see SentryAuth}
     */
    private $sdkIdentifier = \Sentry\Client::SDK_IDENTIFIER;
    /**
     * @var string The SDK version of the Client
     */
    private $sdkVersion;
    /**
     * Class constructor.
     *
     * @param Options|null $options The client options
     */
    public function __construct(\Sentry\Options $options = null)
    {
        $this->options = $options ?? new \Sentry\Options();
        $this->sdkVersion = \WPSentry\ScopedVendor\Jean85\PrettyVersions::getVersion('sentry/sentry')->getPrettyVersion();
    }
    /**
     * {@inheritdoc}
     */
    public static function create(array $options = []) : \Sentry\ClientBuilderInterface
    {
        return new static(new \Sentry\Options($options));
    }
    /**
     * {@inheritdoc}
     */
    public function getOptions() : \Sentry\Options
    {
        return $this->options;
    }
    /**
     * {@inheritdoc}
     */
    public function setUriFactory(\WPSentry\ScopedVendor\Http\Message\UriFactory $uriFactory) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        $this->uriFactory = $uriFactory;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setMessageFactory(\WPSentry\ScopedVendor\Http\Message\MessageFactory $messageFactory) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        $this->messageFactory = $messageFactory;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setTransport(\Sentry\Transport\TransportInterface $transport) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0. Use the setTransportFactory() method instead.', __METHOD__), \E_USER_DEPRECATED);
        $this->transport = $transport;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setHttpClient(\WPSentry\ScopedVendor\Http\Client\HttpAsyncClient $httpClient) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        $this->httpClient = $httpClient;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function addHttpClientPlugin(\WPSentry\ScopedVendor\Http\Client\Common\Plugin $plugin) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        $this->httpClientPlugins[] = $plugin;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function removeHttpClientPlugin(string $className) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.3 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        foreach ($this->httpClientPlugins as $index => $httpClientPlugin) {
            if (!$httpClientPlugin instanceof $className) {
                continue;
            }
            unset($this->httpClientPlugins[$index]);
        }
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setSerializer(\Sentry\Serializer\SerializerInterface $serializer) : \Sentry\ClientBuilderInterface
    {
        $this->serializer = $serializer;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setRepresentationSerializer(\Sentry\Serializer\RepresentationSerializerInterface $representationSerializer) : \Sentry\ClientBuilderInterface
    {
        $this->representationSerializer = $representationSerializer;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setSdkIdentifier(string $sdkIdentifier) : \Sentry\ClientBuilderInterface
    {
        $this->sdkIdentifier = $sdkIdentifier;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setSdkVersion(string $sdkVersion) : \Sentry\ClientBuilderInterface
    {
        $this->sdkVersion = $sdkVersion;
        return $this;
    }
    /**
     * Sets the version of the SDK package that generated this Event using the Packagist name.
     *
     * @param string $packageName The package name that will be used to get the version from (i.e. "sentry/sentry")
     *
     * @return $this
     *
     * @deprecated since version 2.2, to be removed in 3.0
     */
    public function setSdkVersionByPackageName(string $packageName) : \Sentry\ClientBuilderInterface
    {
        @\trigger_error(\sprintf('Method %s() is deprecated since version 2.2 and will be removed in 3.0.', __METHOD__), \E_USER_DEPRECATED);
        $this->sdkVersion = \WPSentry\ScopedVendor\Jean85\PrettyVersions::getVersion($packageName)->getPrettyVersion();
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getClient() : \Sentry\ClientInterface
    {
        $this->transport = $this->transport ?? $this->createTransportInstance();
        return new \Sentry\Client($this->options, $this->transport, $this->createEventFactory());
    }
    /**
     * Sets the transport factory.
     *
     * @param TransportFactoryInterface $transportFactory The transport factory
     *
     * @return $this
     */
    public function setTransportFactory(\Sentry\Transport\TransportFactoryInterface $transportFactory) : \Sentry\ClientBuilderInterface
    {
        $this->transportFactory = $transportFactory;
        return $this;
    }
    /**
     * Creates a new instance of the transport mechanism.
     */
    private function createTransportInstance() : \Sentry\Transport\TransportInterface
    {
        if (null !== $this->transport) {
            return $this->transport;
        }
        $transportFactory = $this->transportFactory ?? $this->createDefaultTransportFactory();
        return $transportFactory->create($this->options);
    }
    /**
     * Instantiate the {@see EventFactory} with the configured serializers.
     */
    private function createEventFactory() : \Sentry\EventFactoryInterface
    {
        $this->serializer = $this->serializer ?? new \Sentry\Serializer\Serializer($this->options);
        $this->representationSerializer = $this->representationSerializer ?? new \Sentry\Serializer\RepresentationSerializer($this->options);
        return new \Sentry\EventFactory($this->serializer, $this->representationSerializer, $this->options, $this->sdkIdentifier, $this->sdkVersion);
    }
    /**
     * Creates a new instance of the {@see DefaultTransportFactory} factory.
     */
    private function createDefaultTransportFactory() : \Sentry\Transport\DefaultTransportFactory
    {
        $this->messageFactory = $this->messageFactory ?? \WPSentry\ScopedVendor\Http\Discovery\MessageFactoryDiscovery::find();
        $this->uriFactory = $this->uriFactory ?? \WPSentry\ScopedVendor\Http\Discovery\UriFactoryDiscovery::find();
        $this->streamFactory = $this->streamFactory ?? \WPSentry\ScopedVendor\Http\Discovery\StreamFactoryDiscovery::find();
        $httpClientFactory = new \Sentry\HttpClient\HttpClientFactory($this->uriFactory, $this->messageFactory, $this->streamFactory, $this->httpClient, $this->sdkIdentifier, $this->sdkVersion);
        if (!empty($this->httpClientPlugins)) {
            $httpClientFactory = new \Sentry\HttpClient\PluggableHttpClientFactory($httpClientFactory, $this->httpClientPlugins);
        }
        return new \Sentry\Transport\DefaultTransportFactory($this->messageFactory, $httpClientFactory);
    }
}
