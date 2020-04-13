<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class KirbyInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'site/plugins/{$name}/', 'field' => 'site/fields/{$name}/', 'tag' => 'site/tags/{$name}/');
}
