<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class EliasisInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('component' => 'components/{$name}/', 'module' => 'modules/{$name}/', 'plugin' => 'plugins/{$name}/', 'template' => 'templates/{$name}/');
}
