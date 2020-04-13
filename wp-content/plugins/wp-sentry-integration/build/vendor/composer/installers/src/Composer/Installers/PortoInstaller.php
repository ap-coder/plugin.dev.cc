<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class PortoInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('container' => 'app/Containers/{$name}/');
}
