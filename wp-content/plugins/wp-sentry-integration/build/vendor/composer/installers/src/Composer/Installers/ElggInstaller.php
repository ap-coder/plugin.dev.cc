<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ElggInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'mod/{$name}/');
}
