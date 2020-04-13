<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class AimeosInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('extension' => 'ext/{$name}/');
}
