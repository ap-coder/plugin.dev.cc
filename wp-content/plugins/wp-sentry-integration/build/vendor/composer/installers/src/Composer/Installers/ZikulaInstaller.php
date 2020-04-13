<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ZikulaInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$vendor}-{$name}/', 'theme' => 'themes/{$vendor}-{$name}/');
}
