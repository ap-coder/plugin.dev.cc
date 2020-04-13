<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class MakoInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('package' => 'app/packages/{$name}/');
}
