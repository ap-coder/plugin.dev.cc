<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class PPIInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/');
}
