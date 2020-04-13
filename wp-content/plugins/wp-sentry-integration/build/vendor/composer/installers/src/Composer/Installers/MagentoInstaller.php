<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class MagentoInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('theme' => 'app/design/frontend/{$name}/', 'skin' => 'skin/frontend/default/{$name}/', 'library' => 'lib/{$name}/');
}
