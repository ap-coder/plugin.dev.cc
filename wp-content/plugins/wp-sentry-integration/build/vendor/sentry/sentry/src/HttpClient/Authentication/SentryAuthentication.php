<?php

declare (strict_types=1);
namespace Sentry\HttpClient\Authentication;

use WPSentry\ScopedVendor\Http\Message\Authentication;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use Sentry\Client;
use Sentry\Exception\MissingPublicKeyCredentialException;
use Sentry\Options;
/**
 * This authentication method sends the requests along with a X-Sentry-Auth
 * header.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class SentryAuthentication implements \WPSentry\ScopedVendor\Http\Message\Authentication
{
    /**
     * @var Options The Sentry client configuration
     */
    private $options;
    /**
     * @var string The SDK identifier
     */
    private $sdkIdentifier;
    /**
     * @var string The SDK version
     */
    private $sdkVersion;
    /**
     * Constructor.
     *
     * @param Options $options       The Sentry client configuration
     * @param string  $sdkIdentifier The Sentry SDK identifier in use
     * @param string  $sdkVersion    The Sentry SDK version in use
     */
    public function __construct(\Sentry\Options $options, string $sdkIdentifier, string $sdkVersion)
    {
        $this->options = $options;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->sdkVersion = $sdkVersion;
    }
    /**
     * {@inheritdoc}
     *
     * @throws MissingPublicKeyCredentialException If the public key is missing in the DSN
     */
    public function authenticate(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface
    {
        $publicKey = $this->options->getPublicKey();
        $secretKey = $this->options->getSecretKey();
        if (null === $publicKey) {
            throw new \Sentry\Exception\MissingPublicKeyCredentialException();
        }
        $data = ['sentry_version' => \Sentry\Client::PROTOCOL_VERSION, 'sentry_client' => $this->sdkIdentifier . '/' . $this->sdkVersion, 'sentry_key' => $publicKey];
        if (null !== $secretKey) {
            $data['sentry_secret'] = $secretKey;
        }
        $headers = [];
        foreach ($data as $headerKey => $headerValue) {
            $headers[] = $headerKey . '=' . $headerValue;
        }
        /** @var RequestInterface $request */
        $request = $request->withHeader('X-Sentry-Auth', 'Sentry ' . \implode(', ', $headers));
        return $request;
    }
}