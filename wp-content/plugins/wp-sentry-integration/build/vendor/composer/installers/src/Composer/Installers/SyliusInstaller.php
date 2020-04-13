<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class SyliusInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('theme' => 'themes/{$name}/');
}
