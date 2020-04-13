<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ClanCatsFrameworkInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('ship' => 'CCF/orbit/{$name}/', 'theme' => 'CCF/app/themes/{$name}/');
}
