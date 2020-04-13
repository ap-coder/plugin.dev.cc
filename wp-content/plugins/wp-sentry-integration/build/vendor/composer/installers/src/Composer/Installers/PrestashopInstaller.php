<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class PrestashopInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/', 'theme' => 'themes/{$name}/');
}
