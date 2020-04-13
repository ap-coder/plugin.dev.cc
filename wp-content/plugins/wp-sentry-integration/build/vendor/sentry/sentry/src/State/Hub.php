<?php

declare (strict_types=1);
namespace Sentry\State;

use Sentry\Breadcrumb;
use Sentry\ClientInterface;
use Sentry\Integration\IntegrationInterface;
use Sentry\SentrySdk;
use Sentry\Severity;
/**
 * This class is a basic implementation of the {@see HubInterface} interface.
 */
final class Hub implements \Sentry\State\HubInterface
{
    /**
     * @var Layer[] The stack of client/scope pairs
     */
    private $stack = [];
    /**
     * @var string|null The ID of the last captured event
     */
    private $lastEventId;
    /**
     * Hub constructor.
     *
     * @param ClientInterface|null $client The client bound to the hub
     * @param Scope|null           $scope  The scope bound to the hub
     */
    public function __construct(?\Sentry\ClientInterface $client = null, ?\Sentry\State\Scope $scope = null)
    {
        $this->stack[] = new \Sentry\State\Layer($client, $scope ?? new \Sentry\State\Scope());
    }
    /**
     * {@inheritdoc}
     */
    public function getClient() : ?\Sentry\ClientInterface
    {
        return $this->getStackTop()->getClient();
    }
    /**
     * {@inheritdoc}
     */
    public function getLastEventId() : ?string
    {
        return $this->lastEventId;
    }
    /**
     * {@inheritdoc}
     */
    public function pushScope() : \Sentry\State\Scope
    {
        $clonedScope = clone $this->getScope();
        $this->stack[] = new \Sentry\State\Layer($this->getClient(), $clonedScope);
        return $clonedScope;
    }
    /**
     * {@inheritdoc}
     */
    public function popScope() : bool
    {
        if (1 === \count($this->stack)) {
            return \false;
        }
        return null !== \array_pop($this->stack);
    }
    /**
     * {@inheritdoc}
     */
    public function withScope(callable $callback) : void
    {
        $scope = $this->pushScope();
        try {
            $callback($scope);
        } finally {
            $this->popScope();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureScope(callable $callback) : void
    {
        $callback($this->getScope());
    }
    /**
     * {@inheritdoc}
     */
    public function bindClient(\Sentry\ClientInterface $client) : void
    {
        $layer = $this->getStackTop();
        $layer->setClient($client);
    }
    /**
     * {@inheritdoc}
     */
    public function captureMessage(string $message, ?\Sentry\Severity $level = null) : ?string
    {
        $client = $this->getClient();
        if (null !== $client) {
            return $this->lastEventId = $client->captureMessage($message, $level, $this->getScope());
        }
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function captureException(\Throwable $exception) : ?string
    {
        $client = $this->getClient();
        if (null !== $client) {
            return $this->lastEventId = $client->captureException($exception, $this->getScope());
        }
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function captureEvent(array $payload) : ?string
    {
        $client = $this->getClient();
        if (null !== $client) {
            return $this->lastEventId = $client->captureEvent($payload, $this->getScope());
        }
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function captureLastError() : ?string
    {
        $client = $this->getClient();
        if (null !== $client) {
            return $this->lastEventId = $client->captureLastError($this->getScope());
        }
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function addBreadcrumb(\Sentry\Breadcrumb $breadcrumb) : bool
    {
        $client = $this->getClient();
        if (null === $client) {
            return \false;
        }
        $options = $client->getOptions();
        $beforeBreadcrumbCallback = $options->getBeforeBreadcrumbCallback();
        $maxBreadcrumbs = $options->getMaxBreadcrumbs();
        if ($maxBreadcrumbs <= 0) {
            return \false;
        }
        $breadcrumb = \call_user_func($beforeBreadcrumbCallback, $breadcrumb);
        if (null !== $breadcrumb) {
            $this->getScope()->addBreadcrumb($breadcrumb, $maxBreadcrumbs);
        }
        return null !== $breadcrumb;
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
        @\trigger_error(\sprintf('The %s() method is deprecated since version 2.2 and will be removed in 3.0. Use SentrySdk::setCurrentHub() instead.', __METHOD__), \E_USER_DEPRECATED);
        \Sentry\SentrySdk::setCurrentHub($hub);
        return $hub;
    }
    /**
     * {@inheritdoc}
     */
    public function getIntegration(string $className) : ?\Sentry\Integration\IntegrationInterface
    {
        $client = $this->getClient();
        if (null !== $client) {
            return $client->getIntegration($className);
        }
        return null;
    }
    /**
     * Gets the scope bound to the top of the stack.
     */
    private function getScope() : \Sentry\State\Scope
    {
        return $this->getStackTop()->getScope();
    }
    /**
     * Gets the topmost client/layer pair in the stack.
     */
    private function getStackTop() : \Sentry\State\Layer
    {
        return $this->stack[\count($this->stack) - 1];
    }
}
