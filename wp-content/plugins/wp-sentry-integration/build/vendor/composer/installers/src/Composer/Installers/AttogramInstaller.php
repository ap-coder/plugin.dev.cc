<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class AttogramInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/');
}
