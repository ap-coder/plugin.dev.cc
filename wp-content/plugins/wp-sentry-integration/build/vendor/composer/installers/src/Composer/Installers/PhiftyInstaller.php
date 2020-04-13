<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class PhiftyInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('bundle' => 'bundles/{$name}/', 'library' => 'libraries/{$name}/', 'framework' => 'frameworks/{$name}/');
}
