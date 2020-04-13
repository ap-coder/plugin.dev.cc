<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class KodiCMSInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'cms/plugins/{$name}/', 'media' => 'cms/media/vendor/{$name}/');
}
