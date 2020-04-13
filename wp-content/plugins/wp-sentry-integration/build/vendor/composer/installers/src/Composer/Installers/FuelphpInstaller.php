<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class FuelphpInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('component' => 'components/{$name}/');
}
