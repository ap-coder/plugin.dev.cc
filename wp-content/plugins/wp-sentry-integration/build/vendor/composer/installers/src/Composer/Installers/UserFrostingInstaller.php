<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class UserFrostingInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('sprinkle' => 'app/sprinkles/{$name}/');
}
