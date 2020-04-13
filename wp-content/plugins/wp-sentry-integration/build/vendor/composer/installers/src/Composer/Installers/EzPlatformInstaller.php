<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class EzPlatformInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('meta-assets' => 'web/assets/ezplatform/', 'assets' => 'web/assets/ezplatform/{$name}/');
}
