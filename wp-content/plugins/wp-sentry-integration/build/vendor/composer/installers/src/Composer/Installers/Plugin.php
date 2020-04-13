<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

use WPSentry\ScopedVendor\Composer\Composer;
use WPSentry\ScopedVendor\Composer\IO\IOInterface;
use WPSentry\ScopedVendor\Composer\Plugin\PluginInterface;
class Plugin implements \WPSentry\ScopedVendor\Composer\Plugin\PluginInterface
{
    public function activate(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
        $installer = new \WPSentry\ScopedVendor\Composer\Installers\Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
