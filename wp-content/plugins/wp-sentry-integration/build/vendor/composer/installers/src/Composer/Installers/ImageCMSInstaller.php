<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ImageCMSInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('template' => 'templates/{$name}/', 'module' => 'application/modules/{$name}/', 'library' => 'application/libraries/{$name}/');
}
