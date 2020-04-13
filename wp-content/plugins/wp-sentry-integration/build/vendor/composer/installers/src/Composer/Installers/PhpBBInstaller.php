<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class PhpBBInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('extension' => 'ext/{$vendor}/{$name}/', 'language' => 'language/{$name}/', 'style' => 'styles/{$name}/');
}
