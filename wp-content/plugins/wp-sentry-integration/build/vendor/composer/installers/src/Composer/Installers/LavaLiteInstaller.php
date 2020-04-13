<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class LavaLiteInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('package' => 'packages/{$vendor}/{$name}/', 'theme' => 'public/themes/{$name}/');
}
