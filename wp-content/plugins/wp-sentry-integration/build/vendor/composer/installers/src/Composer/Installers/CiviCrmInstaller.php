<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class CiviCrmInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('ext' => 'ext/{$name}/');
}
