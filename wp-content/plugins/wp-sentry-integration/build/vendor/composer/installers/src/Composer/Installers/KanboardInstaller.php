<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

/**
 *
 * Installer for kanboard plugins
 *
 * kanboard.net
 *
 * Class KanboardInstaller
 * @package Composer\Installers
 */
class KanboardInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'plugins/{$name}/');
}
