<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class DframeInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$vendor}/{$name}/');
}
