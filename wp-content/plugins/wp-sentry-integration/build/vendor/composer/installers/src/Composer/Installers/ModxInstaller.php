<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

/**
 * An installer to handle MODX specifics when installing packages.
 */
class ModxInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('extra' => 'core/packages/{$name}/');
}
