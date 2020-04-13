<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class BonefishInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('package' => 'Packages/{$vendor}/{$name}/');
}
