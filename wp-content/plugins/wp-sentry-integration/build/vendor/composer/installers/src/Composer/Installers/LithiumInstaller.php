<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class LithiumInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('library' => 'libraries/{$name}/', 'source' => 'libraries/_source/{$name}/');
}
