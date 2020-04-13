<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ItopInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('extension' => 'extensions/{$name}/');
}
