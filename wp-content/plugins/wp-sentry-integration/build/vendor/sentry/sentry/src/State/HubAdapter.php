<?php

declare (strict_types=1);
namespace Sentry\State;

use Sentry\Breadcrumb;
use Sentry\ClientInterface;
use Sentry\Integration\IntegrationInterface;
use Sentry\SentrySdk;
use Sentry\Severity;
/**
 * An implementation of {@see HubInterface} that uses {@see SentrySdk} internally
 * to manage the current hub.
 */
final class HubAdapter implements \Sentry\State\HubInterface
{
    /**
     * @var self|null The single instance which forwards all calls to {@see SentrySdk}
     */
    private static $instance;
    /**
     * Constructor.
     */
    private function __construct()
    {
    }
    /**
     * Gets the instance of this class. This is a singleton, so once initialized
     * you will always get the same instance.
     */
    public static function getInstance() : self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * {@inheritdoc}
     */
    public function getClient() : ?\Sentry\ClientInterface
    {
        return \Sentry\SentrySdk::getCurrentHub()->getClient();
    }
    /**
     * {@inheritdoc}
     */
    public function getLastEventId() : ?string
    {
        return \Sentry\SentrySdk::getCurrentHub()->getLastEventId();
    }
    /**
     * {@inheritdoc}
     */
    public function pushScope() : \Sentry\State\Scope
    {
        return \Sentry\SentrySdk::getCurrentHub()->pushScope();
    }
    /**
     * {@inheritdoc}
     */
    public function popScope() : bool
    {
        return \Sentry\SentrySdk::getCurrentHub()->popScope();
    }
    /**
     * {@inheritdoc}
     */
    public function withScope(callable $callback) : void
    {
        \Sentry\SentrySdk::getCurrentHub()->withScope($callback);
    }
    /**
     * {@inheritdoc}
     */
    public function configureScope(callable $callback) : void
    {
        \Sentry\SentrySdk::getCurrentHub()->configureScope($callback);
    }
    /**
     * {@inheritdoc}
     */
    public function bindClient(\Sentry\ClientInterface $client) : void
    {
        \Sentry\SentrySdk::getCurrentHub()->bindClient($client);
    }
    /**
     * {@inheritdoc}
     */
    public function captureMessage(string $message, ?\Sentry\Severity $level = null) : ?string
    {
        return \Sentry\SentrySdk::getCurrentHub()->captureMessage($message, $level);
    }
    /**
     * {@inheritdoc}
     */
    public function captureException(\Throwable $exception) : ?string
    {
        return \Sentry\SentrySdk::getCurrentHub()->captureException($exception);
    }
    /**
     * {@inheritdoc}
     */
    public function captureEvent(array $payload) : ?string
    {
        return \Sentry\SentrySdk::getCurrentHub()->captureEvent($payload);
    }
    /**
     * {@inheritdoc}
     */
    public function captureLastError() : ?string
    {
        return \Sentry\SentrySdk::getCurrentHub()->captureLastError();
    }
    /**
     * {@inheritdoc}
     */
    public function addBreadcrumb(\Sentry\Breadcrumb $breadcrumb) : bool
    {
        return \Sentry\SentrySdk::getCurrentHub()->addBreadcrumb($breadcrumb);
    }
    /**
     * {@inheritdoc}
     */
    public static function getCurrent() : \Sentry\State\HubInterface
    {
        @\trigger_error(\sprintf('The %s() method is deprecated since version 2.2 and will be removed in 3.0. Use SentrySdk::getCurrentHub() instead.', __METHOD__), \E_USER_DEPRECATED);
        return \Sentry\SentrySdk::getCurrentHub();
    }
    /**
     * {@inheritdoc}
     */
    public static function setCurrent(\Sentry\State\HubInterface $hub) : \Sentry\State\HubInterface
    {
        @\trigger_error(\sprintf('The %s() method is deprecated since version 2.2 and will be removed in 3.0. Use SentrySdk::getCurrentHub() instead.', __METHOD__), \E_USER_DEPRECATED);
        return \Sentry\SentrySdk::setCurrentHub($hub);
    }
    /**
     * {@inheritdoc}
     */
    public function getIntegration(string $className) : ?\Sentry\Integration\IntegrationInterface
    {
        return \Sentry\SentrySdk::getCurrentHub()->getIntegration($className);
    }
    /**
     * @see https://www.php.net/manual/en/language.oop5.cloning.php#object.clone
     */
    public function __clone()
    {
        throw new \BadMethodCallException('Cloning is forbidden.');
    }
    /**
     * @see https://www.php.net/manual/en/language.oop5.magic.php#object.wakeup
     */
    public function __wakeup()
    {
        throw new \BadMethodCallException('Unserializing instances of this class is forbidden.');
    }
}
