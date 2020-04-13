<?php

declare (strict_types=1);
namespace Sentry\Transport;

use WPSentry\ScopedVendor\Http\Message\MessageFactory as MessageFactoryInterface;
use Sentry\HttpClient\HttpClientFactoryInterface;
use Sentry\Options;
/**
 * This class is the default implementation of the {@see TransportFactoryInterface}
 * interface.
 */
final class DefaultTransportFactory implements \Sentry\Transport\TransportFactoryInterface
{
    /**
     * @var MessageFactoryInterface The PSR-7 message factory
     */
    private $messageFactory;
    /**
     * @var HttpClientFactoryInterface The factory to create the HTTP client
     */
    private $httpClientFactory;
    /**
     * Constructor.
     *
     * @param MessageFactoryInterface    $messageFactory    The PSR-7 message factory
     * @param HttpClientFactoryInterface $httpClientFactory The HTTP client factory
     */
    public function __construct(\WPSentry\ScopedVendor\Http\Message\MessageFactory $messageFactory, \Sentry\HttpClient\HttpClientFactoryInterface $httpClientFactory)
    {
        $this->messageFactory = $messageFactory;
        $this->httpClientFactory = $httpClientFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function create(\Sentry\Options $options) : \Sentry\Transport\TransportInterface
    {
        if (null === $options->getDsn()) {
            return new \Sentry\Transport\NullTransport();
        }
        return new \Sentry\Transport\HttpTransport($options, $this->httpClientFactory->create($options), $this->messageFactory, \true, \false);
    }
}
