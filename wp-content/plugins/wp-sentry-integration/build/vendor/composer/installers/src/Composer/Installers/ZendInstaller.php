<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class ZendInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('library' => 'library/{$name}/', 'extra' => 'extras/library/{$name}/', 'module' => 'module/{$name}/');
}
