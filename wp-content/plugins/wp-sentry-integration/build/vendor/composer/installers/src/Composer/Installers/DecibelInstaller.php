<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class DecibelInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    /** @var array */
    protected $locations = array('app' => 'app/{$name}/');
}
