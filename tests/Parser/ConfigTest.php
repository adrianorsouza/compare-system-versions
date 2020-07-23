<?php

use Adrianorosa\Csv\Parser\Config;
use Adrianorosa\Csv\Parser\ScanSystem;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-22 18:57
 *
 */
class ConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = [
            "system_versions" => [
                "latest" => [
                    "php" => "7.4.7" ,
                    "nginx" => "1.18.0",
                    "mysql" => "8.0.20",
                    "platform" => "Ubuntu 20.04 LTS (Focal Fossa)"
                ],
                "current" => [
                    "php" => "7.3.1",
                    "nginx" => "1.16.0",
                    "mysql" => "8.0.20",
                    "platform" => "Ubuntu 18.04 LTS (Bionic Beaver)"
                ]
            ]  ,
            "package_versions" => [
                "latest" => [
                    "phpunit" => "9.2.0",
                    "laravel" => "7.18.0",
                    "react" => "16.13.1",
                    "grunt" => "1.2.1",
                ]
            ],
            "iterator" => [
                "dir" => "./",
                "packages" => [
                    "composer.lock",
                    "package-lock.json",
                    "bower.json",
                ]  ,
                "exclude" => [
                    "bower_components",
                    "node_modules",
                    "public",
                    "public_html",
                    "vendor",
                    "compare-system-versions",
                    "XML-WSDL-builder",
                ]
            ],
            "options" => [
                "title" => "System Versions Compare",
                "build_dir" => "_build/"
            ]
        ];

        $config = new Config($config);

        $this->assertNull($config->getPackageVersionsLatest('foo'));
        $this->assertEquals('9.2.0', $config->getPackageVersionsLatest('phpunit'));
        $this->assertEquals('7.18.0', $config->getPackageVersionsLatest('laravel'));
        $this->assertEquals('16.13.1', $config->getPackageVersionsLatest('react'));
        $this->assertEquals('1.2.1', $config->getPackageVersionsLatest('grunt'));

        $this->assertNull($config->getSystemVersionsLatest('foo'));
        $this->assertEquals('7.4.7', $config->getSystemVersionsLatest('php'));
        $this->assertEquals('1.18.0', $config->getSystemVersionsLatest('nginx'));
        $this->assertEquals('8.0.20', $config->getSystemVersionsLatest('mysql'));
        $this->assertEquals('Ubuntu 20.04 LTS (Focal Fossa)', $config->getSystemVersionsLatest('platform'));
    }

}
