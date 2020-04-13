<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class LaravelInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('library' => 'libraries/{$name}/');
}
