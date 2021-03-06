<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('ComposerAutoloaderInit2282ecd9f1d023b2745b96ca1671620e', false)) {
    class_exists('WPSentry\ScopedVendor\ComposerAutoloaderInit2282ecd9f1d023b2745b96ca1671620e');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('composerRequire2282ecd9f1d023b2745b96ca1671620e')) {
    function composerRequire2282ecd9f1d023b2745b96ca1671620e() {
        return \WPSentry\ScopedVendor\composerRequire2282ecd9f1d023b2745b96ca1671620e(...func_get_args());
    }
}
if (!function_exists('includeIfExists')) {
    function includeIfExists() {
        return \WPSentry\ScopedVendor\includeIfExists(...func_get_args());
    }
}
if (!function_exists('uuid_create')) {
    function uuid_create() {
        return \WPSentry\ScopedVendor\uuid_create(...func_get_args());
    }
}
if (!function_exists('uuid_generate_md5')) {
    function uuid_generate_md5() {
        return \WPSentry\ScopedVendor\uuid_generate_md5(...func_get_args());
    }
}
if (!function_exists('uuid_generate_sha1')) {
    function uuid_generate_sha1() {
        return \WPSentry\ScopedVendor\uuid_generate_sha1(...func_get_args());
    }
}
if (!function_exists('uuid_is_valid')) {
    function uuid_is_valid() {
        return \WPSentry\ScopedVendor\uuid_is_valid(...func_get_args());
    }
}
if (!function_exists('uuid_compare')) {
    function uuid_compare() {
        return \WPSentry\ScopedVendor\uuid_compare(...func_get_args());
    }
}
if (!function_exists('uuid_is_null')) {
    function uuid_is_null() {
        return \WPSentry\ScopedVendor\uuid_is_null(...func_get_args());
    }
}
if (!function_exists('uuid_type')) {
    function uuid_type() {
        return \WPSentry\ScopedVendor\uuid_type(...func_get_args());
    }
}
if (!function_exists('uuid_variant')) {
    function uuid_variant() {
        return \WPSentry\ScopedVendor\uuid_variant(...func_get_args());
    }
}
if (!function_exists('uuid_time')) {
    function uuid_time() {
        return \WPSentry\ScopedVendor\uuid_time(...func_get_args());
    }
}
if (!function_exists('uuid_mac')) {
    function uuid_mac() {
        return \WPSentry\ScopedVendor\uuid_mac(...func_get_args());
    }
}
if (!function_exists('uuid_parse')) {
    function uuid_parse() {
        return \WPSentry\ScopedVendor\uuid_parse(...func_get_args());
    }
}
if (!function_exists('uuid_unparse')) {
    function uuid_unparse() {
        return \WPSentry\ScopedVendor\uuid_unparse(...func_get_args());
    }
}

return $loader;
