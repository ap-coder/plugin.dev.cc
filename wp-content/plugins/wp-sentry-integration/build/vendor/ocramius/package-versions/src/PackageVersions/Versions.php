<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\PackageVersions;

/**
 * This class is generated by ocramius/package-versions, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 */
final class Versions
{
    public const ROOT_PACKAGE_NAME = 'stayallive/wp-sentry';
    public const VERSIONS = array('clue/stream-filter' => 'v1.4.1@5a58cc30a8bd6a4eb8f856adf61dd3e013f53f71', 'composer/installers' => 'v1.8.0@7d610d50aae61ae7ed6675e58efeabdf279bb5e3', 'guzzlehttp/promises' => 'v1.3.1@a59da6cf61d80060647ff4d3eb2c03a2bc694646', 'guzzlehttp/psr7' => '1.6.1@239400de7a173fe9901b9ac7c06497751f00727a', 'http-interop/http-factory-guzzle' => '1.0.0@34861658efb9899a6618cef03de46e2a52c80fc0', 'jean85/pretty-package-versions' => '1.2@75c7effcf3f77501d0e0caa75111aff4daa0dd48', 'ocramius/package-versions' => '1.4.2@44af6f3a2e2e04f2af46bcb302ad9600cba41c7d', 'paragonie/random_compat' => 'v9.99.99@84b4dfb120c6f9b4ff7b3685f9b8f1aa365a0c95', 'php-http/client-common' => '2.1.0@a8b29678d61556f45d6236b1667db16d998ceec5', 'php-http/curl-client' => '2.1.0@9e79355af46d72e10da50be20b66f74b26143441', 'php-http/discovery' => '1.7.4@82dbef649ccffd8e4f22e1953c3a5265992b83c0', 'php-http/httplug' => '2.1.0@72d2b129a48f0490d55b7f89be0d6aa0597ffb06', 'php-http/message' => '1.8.0@ce8f43ac1e294b54aabf5808515c3554a19c1e1c', 'php-http/message-factory' => 'v1.0.2@a478cb11f66a6ac48d8954216cfed9aa06a501a1', 'php-http/promise' => 'v1.0.0@dc494cdc9d7160b9a09bd5573272195242ce7980', 'psr/http-client' => '1.0.0@496a823ef742b632934724bf769560c2a5c7c44e', 'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be', 'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363', 'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822', 'sentry/sentry' => '2.3.2@b3e71feb32f1787b66a3b4fdb8686972e9c7ba94', 'symfony/options-resolver' => 'v3.4.39@730ef56164ed6c9356c159e9f5ff2b84d753b9ed', 'symfony/polyfill-uuid' => 'v1.15.0@2318f7f470a892867f3de602e403d006b1b9c9aa', 'stayallive/wp-sentry' => 'dev-9818021cbae6bb6839b8bc00467ff122ca033f18@9818021cbae6bb6839b8bc00467ff122ca033f18');
    private function __construct()
    {
    }
    /**
     * @throws \OutOfBoundsException If a version cannot be located.
     */
    public static function getVersion(string $packageName) : string
    {
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
