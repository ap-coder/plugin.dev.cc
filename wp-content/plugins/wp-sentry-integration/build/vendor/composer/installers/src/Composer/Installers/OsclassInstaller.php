<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class OsclassInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'oc-content/plugins/{$name}/', 'theme' => 'oc-content/themes/{$name}/', 'language' => 'oc-content/languages/{$name}/');
}
