<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

/**
 * An installer to handle TAO extensions.
 */
class TaoInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('extension' => '{$name}');
}
