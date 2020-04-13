<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class AnnotateCmsInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'addons/modules/{$name}/', 'component' => 'addons/components/{$name}/', 'service' => 'addons/services/{$name}/');
}
