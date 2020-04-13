<?php

/**
 * Created by PhpStorm.
 * User: cbleek
 * Date: 25.03.16
 * Time: 20:55
 */
namespace WPSentry\ScopedVendor\Composer\Installers;

class YawikInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'module/{$name}/');
    /**
     * Format package name to CamelCase
     * @param array $vars
     *
     * @return array
     */
    public function inflectPackageVars($vars)
    {
        $vars['name'] = \strtolower(\preg_replace('/(?<=\\w)([A-Z])/', 'WPSentry\\ScopedVendor\\_\\1', $vars['name']));
        $vars['name'] = \str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = \str_replace(' ', '', \ucwords($vars['name']));
        return $vars;
    }
}
