<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class WolfCMSInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'wolf/plugins/{$name}/');
}
