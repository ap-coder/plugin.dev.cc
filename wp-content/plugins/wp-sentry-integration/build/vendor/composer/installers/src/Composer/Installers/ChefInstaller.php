<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ChefInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('cookbook' => 'Chef/{$vendor}/{$name}/', 'role' => 'Chef/roles/{$name}/');
}
