<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class MODULEWorkInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/');
}
