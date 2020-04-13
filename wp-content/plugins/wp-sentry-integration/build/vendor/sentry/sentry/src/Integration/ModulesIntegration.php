<?php

declare (strict_types=1);
namespace Sentry\Integration;

use WPSentry\ScopedVendor\Jean85\PrettyVersions;
use WPSentry\ScopedVendor\PackageVersions\Versions;
use Sentry\Event;
use Sentry\SentrySdk;
use Sentry\State\Scope;
/**
 * This integration logs with the event details all the versions of the packages
 * installed with Composer; the root project is included too.
 */
final class ModulesIntegration implements \Sentry\Integration\IntegrationInterface
{
    /**
     * @var array The list of installed vendors
     */
    private static $loadedModules = [];
    /**
     * {@inheritdoc}
     */
    public function setupOnce() : void
    {
        \Sentry\State\Scope::addGlobalEventProcessor(function (\Sentry\Event $event) {
            $integration = \Sentry\SentrySdk::getCurrentHub()->getIntegration(self::class);
            // The integration could be bound to a client that is not the one
            // attached to the current hub. If this is the case, bail out
            if ($integration instanceof self) {
                self::applyToEvent($integration, $event);
            }
            return $event;
        });
    }
    /**
     * Applies the information gathered by this integration to the event.
     *
     * @param self  $self  The instance of this integration
     * @param Event $event The event that will be enriched with the modules
     */
    public static function applyToEvent(self $self, \Sentry\Event $event) : void
    {
        if (empty(self::$loadedModules)) {
            foreach (\WPSentry\ScopedVendor\PackageVersions\Versions::VERSIONS as $package => $rawVersion) {
                self::$loadedModules[$package] = \WPSentry\ScopedVendor\Jean85\PrettyVersions::getVersion($package)->getPrettyVersion();
            }
        }
        $event->setModules(self::$loadedModules);
    }
}
