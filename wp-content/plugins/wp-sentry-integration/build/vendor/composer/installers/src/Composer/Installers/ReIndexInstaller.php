<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ReIndexInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('theme' => 'themes/{$name}/', 'plugin' => 'plugins/{$name}/');
}
