<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class VanillaInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'plugins/{$name}/', 'theme' => 'themes/{$name}/');
}
