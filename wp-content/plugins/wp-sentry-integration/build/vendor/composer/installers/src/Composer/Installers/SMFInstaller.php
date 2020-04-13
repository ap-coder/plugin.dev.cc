<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class SMFInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'Sources/{$name}/', 'theme' => 'Themes/{$name}/');
}
