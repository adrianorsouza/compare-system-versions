<?php

use Adrianorosa\Csv\Parser\Config;
use Adrianorosa\Csv\Parser\ScanSystem;
use PHPUnit\Framework\TestCase;

/**
 * Class ScamSystemTest.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-15 15:15
 *
 */
class ScamSystemTest extends TestCase
{
    public function testScanSystemVersions()
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
                    "phpunit" => "9.2.0,",
                    "laravel" => "7.18.0,",
                    "react" => "16.13.1,",
                    "grunt" => "1.2.1,",
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

        $scanVersions = new ScanSystem(new Config($config));

        $this->assertArrayHasKey('platform', $scanVersions->toArray());
        $this->assertArrayHasKey('php', $scanVersions->toArray());
        $this->assertArrayHasKey('nginx', $scanVersions->toArray());
        $this->assertArrayHasKey('mysql', $scanVersions->toArray());

        $this->assertArrayHasKey('version', $scanVersions->toArray()['platform']);
        $this->assertArrayHasKey('satisfies', $scanVersions->toArray()['platform']);
    }

}
