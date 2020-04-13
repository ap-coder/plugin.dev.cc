<?php

declare (strict_types=1);
namespace Sentry;

use WPSentry\ScopedVendor\Http\Client\Common\Plugin as PluginInterface;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Message\MessageFactory as MessageFactoryInterface;
use WPSentry\ScopedVendor\Http\Message\UriFactory as UriFactoryInterface;
use Sentry\Serializer\RepresentationSerializerInterface;
use Sentry\Serializer\SerializerInterface;
use Sentry\Transport\TransportFactoryInterface;
use Sentry\Transport\TransportInterface;
/**
 * A configurable builder for Client objects.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 *
 * @method self setTransportFactory(TransportFactoryInterface $transportFactory)
 */
interface ClientBuilderInterface
{
    /**
     * Creates a new instance of this builder.
     *
     * @param array $options The client options, in naked array form
     *
     * @return static
     */
    public static function create(array $options = []) : self;
    /**
     * The options that will be used to create the {@see Client}.
     */
    public function getOptions() : \Sentry\Options;
    /**
     * Sets the factory to use to create URIs.
     *
     * @param UriFactoryInterface $uriFactory The factory
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function setUriFactory(\WPSentry\ScopedVendor\Http\Message\UriFactory $uriFactory) : self;
    /**
     * Sets the factory to use to create PSR-7 messages.
     *
     * @param MessageFactoryInterface $messageFactory The factory
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function setMessageFactory(\WPSentry\ScopedVendor\Http\Message\MessageFactory $messageFactory) : self;
    /**
     * Sets the transport that will be used to send events.
     *
     * @param TransportInterface $transport The transport
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function setTransport(\Sentry\Transport\TransportInterface $transport) : self;
    /**
     * Sets the HTTP client.
     *
     * @param HttpAsyncClient $httpClient The HTTP client
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function setHttpClient(\WPSentry\ScopedVendor\Http\Client\HttpAsyncClient $httpClient) : self;
    /**
     * Adds a new HTTP client plugin to the end of the plugins chain.
     *
     * @param PluginInterface $plugin The plugin instance
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function addHttpClientPlugin(\WPSentry\ScopedVendor\Http\Client\Common\Plugin $plugin) : self;
    /**
     * Removes a HTTP client plugin by its fully qualified class name (FQCN).
     *
     * @param string $className The class name
     *
     * @return $this
     *
     * @deprecated Since version 2.3, to be removed in 3.0
     */
    public function removeHttpClientPlugin(string $className) : self;
    /**
     * Gets the instance of the client built using the configured options.
     */
    public function getClient() : \Sentry\ClientInterface;
    /**
     * Sets a serializer instance to be injected as a dependency of the client.
     *
     * @param SerializerInterface $serializer The serializer to be used by the client to fill the events
     *
     * @return $this
     */
    public function setSerializer(\Sentry\Serializer\SerializerInterface $serializer) : self;
    /**
     * Sets a representation serializer instance to be injected as a dependency of the client.
     *
     * @param RepresentationSerializerInterface $representationSerializer The representation serializer, used to serialize function
     *                                                                    arguments in stack traces, to have string representation
     *                                                                    of non-string values
     *
     * @return $this
     */
    public function setRepresentationSerializer(\Sentry\Serializer\RepresentationSerializerInterface $representationSerializer) : self;
    /**
     * Sets the SDK identifier to be passed onto {@see Event} and HTTP User-Agent header.
     *
     * @param string $sdkIdentifier The SDK identifier to be sent in {@see Event} and HTTP User-Agent headers
     *
     * @return $this
     *
     * @internal
     */
    public function setSdkIdentifier(string $sdkIdentifier) : self;
    /**
     * Sets the SDK version to be passed onto {@see Event} and HTTP User-Agent header.
     *
     * @param string $sdkVersion The version of the SDK in use, to be sent alongside the SDK identifier
     *
     * @return $this
     *
     * @internal
     */
    public function setSdkVersion(string $sdkVersion) : self;
}
