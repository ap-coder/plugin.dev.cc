<?php

declare (strict_types=1);
namespace Sentry\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Sentry\Severity;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
/**
 * This Monolog handler logs every message to a Sentry's server using the given
 * hub instance.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class Handler extends \Monolog\Handler\AbstractProcessingHandler
{
    /**
     * @var HubInterface
     */
    private $hub;
    /**
     * Constructor.
     *
     * @param HubInterface $hub    The hub to which errors are reported
     * @param int          $level  The minimum logging level at which this
     *                             handler will be triggered
     * @param bool         $bubble Whether the messages that are handled can
     *                             bubble up the stack or not
     */
    public function __construct(\Sentry\State\HubInterface $hub, $level = \Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        $this->hub = $hub;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $payload = ['level' => $this->getSeverityFromLevel($record['level']), 'message' => $record['message'], 'logger' => 'monolog.' . $record['channel']];
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Throwable) {
            $payload['exception'] = $record['context']['exception'];
        }
        $this->hub->withScope(function (\Sentry\State\Scope $scope) use($record, $payload) : void {
            $scope->setExtra('monolog.channel', $record['channel']);
            $scope->setExtra('monolog.level', $record['level_name']);
            if (isset($record['context']['extra']) && \is_array($record['context']['extra'])) {
                foreach ($record['context']['extra'] as $key => $value) {
                    $scope->setExtra((string) $key, $value);
                }
            }
            if (isset($record['context']['tags']) && \is_array($record['context']['tags'])) {
                foreach ($record['context']['tags'] as $key => $value) {
                    $scope->setTag($key, $value);
                }
            }
            $this->hub->captureEvent($payload);
        });
    }
    /**
     * Translates the Monolog level into the Sentry severity.
     *
     * @param int $level The Monolog log level
     */
    private function getSeverityFromLevel(int $level) : \Sentry\Severity
    {
        switch ($level) {
            case \Monolog\Logger::DEBUG:
                return \Sentry\Severity::debug();
            case \Monolog\Logger::INFO:
            case \Monolog\Logger::NOTICE:
                return \Sentry\Severity::info();
            case \Monolog\Logger::WARNING:
                return \Sentry\Severity::warning();
            case \Monolog\Logger::ERROR:
                return \Sentry\Severity::error();
            case \Monolog\Logger::CRITICAL:
            case \Monolog\Logger::ALERT:
            case \Monolog\Logger::EMERGENCY:
                return \Sentry\Severity::fatal();
            default:
                return \Sentry\Severity::info();
        }
    }
}
