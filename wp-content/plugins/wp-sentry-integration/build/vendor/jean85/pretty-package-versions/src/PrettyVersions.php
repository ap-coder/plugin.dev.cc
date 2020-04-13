<?php

namespace WPSentry\ScopedVendor\Jean85;

use WPSentry\ScopedVendor\PackageVersions\Versions;
class PrettyVersions
{
    const SHORT_COMMIT_LENGTH = 7;
    public static function getVersion(string $packageName) : \WPSentry\ScopedVendor\Jean85\Version
    {
        return new \WPSentry\ScopedVendor\Jean85\Version($packageName, \WPSentry\ScopedVendor\PackageVersions\Versions::getVersion($packageName));
    }
}
