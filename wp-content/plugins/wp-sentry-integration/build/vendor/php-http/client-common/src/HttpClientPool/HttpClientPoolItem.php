<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common\HttpClientPool;

use WPSentry\ScopedVendor\Http\Client\Common\FlexibleHttpClient;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient;
use WPSentry\ScopedVendor\Http\Client\HttpClient;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Http\Client\Exception;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
/**
 * A HttpClientPoolItem represent a HttpClient inside a Pool.
 *
 * It is disabled when a request failed and can be reenabled after a certain number of seconds.
 * It also keep tracks of the current number of open requests the client is currently being sending
 * (only usable for async method).
 *
 * This class is used internally in the client pools and is not supposed to be used anywhere else.
 *
 * @final
 *
 * @internal
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class HttpClientPoolItem implements \WPSentry\ScopedVendor\Http\Client\HttpClient, \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient
{
    /**
     * @var int Number of request this client is currently sending
     */
    private $sendingRequestCount = 0;
    /**
     * @var \DateTime|null Time when this client has been disabled or null if enable
     */
    private $disabledAt;
    /**
     * Number of seconds until this client is enabled again after an error.
     *
     * null: never reenable this client.
     *
     * @var int|null
     */
    private $reenableAfter;
    /**
     * @var FlexibleHttpClient A http client responding to async and sync request
     */
    private $client;
    /**
     * @param ClientInterface|HttpAsyncClient $client
     * @param int|null                        $reenableAfter Number of seconds until this client is enabled again after an error
     */
    public function __construct($client, int $reenableAfter = null)
    {
        $this->client = new \WPSentry\ScopedVendor\Http\Client\Common\FlexibleHttpClient($client);
        $this->reenableAfter = $reenableAfter;
    }
    /**
     * {@inheritdoc}
     */
    public function sendRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        if ($this->isDisabled()) {
            throw new \WPSentry\ScopedVendor\Http\Client\Exception\RequestException('Cannot send the request as this client has been disabled', $request);
        }
        try {
            $this->incrementRequestCount();
            $response = $this->client->sendRequest($request);
            $this->decrementRequestCount();
        } catch (\WPSentry\ScopedVendor\Http\Client\Exception $e) {
            $this->disable();
            $this->decrementRequestCount();
            throw $e;
        }
        return $response;
    }
    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request)
    {
        if ($this->isDisabled()) {
            throw new \WPSentry\ScopedVendor\Http\Client\Exception\RequestException('Cannot send the request as this client has been disabled', $request);
        }
        $this->incrementRequestCount();
        return $this->client->sendAsyncRequest($request)->then(function ($response) {
            $this->decrementRequestCount();
            return $response;
        }, function ($exception) {
            $this->disable();
            $this->decrementRequestCount();
            throw $exception;
        });
    }
    /**
     * Whether this client is disabled or not.
     *
     * If the client was disabled, calling this method checks if the client can
     * be reenabled and if so enables it.
     */
    public function isDisabled() : bool
    {
        if (null !== $this->reenableAfter && null !== $this->disabledAt) {
            // Reenable after a certain time
            $now = new \DateTime();
            if ($now->getTimestamp() - $this->disabledAt->getTimestamp() >= $this->reenableAfter) {
                $this->enable();
                return \false;
            }
            return \true;
        }
        return null !== $this->disabledAt;
    }
    /**
     * Get current number of request that are currently being sent by the underlying HTTP client.
     */
    public function getSendingRequestCount() : int
    {
        return $this->sendingRequestCount;
    }
    /**
     * Increment the request count.
     */
    private function incrementRequestCount() : void
    {
        ++$this->sendingRequestCount;
    }
    /**
     * Decrement the request count.
     */
    private function decrementRequestCount() : void
    {
        --$this->sendingRequestCount;
    }
    /**
     * Enable the current client.
     */
    private function enable() : void
    {
        $this->disabledAt = null;
    }
    /**
     * Disable the current client.
     */
    private function disable() : void
    {
        $this->disabledAt = new \DateTime('now');
    }
}