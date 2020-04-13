<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class Redaxo5Installer extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('addon' => 'redaxo/src/addons/{$name}/', 'bestyle-plugin' => 'redaxo/src/addons/be_style/plugins/{$name}/');
}
