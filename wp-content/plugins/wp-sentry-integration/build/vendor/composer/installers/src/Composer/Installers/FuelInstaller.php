<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class FuelInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'fuel/app/modules/{$name}/', 'package' => 'fuel/packages/{$name}/', 'theme' => 'fuel/app/themes/{$name}/');
}
