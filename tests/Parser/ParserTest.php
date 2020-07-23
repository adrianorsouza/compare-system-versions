<?php

use Adrianorosa\Csv\Parser\Config;
use Adrianorosa\Csv\Parser\Parser;
use PHPUnit\Framework\TestCase;

    /**
     * Class ParserTest.
     *
     * @author Adriano Rosa <https://adrianorosa.com>
     * @date   2020-07-22 19:24
     *
     */
class ParserTest extends TestCase
{
    public function testParserResult()
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
                "dir" => "./tests/Fixtures",
                "packages" => [
                    "composer.lock",
                    "package-lock.json",
                    "bower.json",
                ]  ,
                "exclude" => [
                    "node_modules",
                    "public",
                    "public_html",
                    "vendor",
                ]
            ],
            "options" => [
                "title" => "System Versions Compare",
                "build_dir" => "_build/"
            ]
        ];

        $transactions = [
            "site1.example.com" => [
                "dirname" => "./tests/Fixtures/site1.example.com",
                "composer" => "./tests/Fixtures/site1.example.com/composer.lock",
                "package" => "./tests/Fixtures/site1.example.com/package-lock.json",
            ],
            "site2.example.com" => [
                "dirname" => "./tests/Fixtures/site2.example.com",
                "bower" => "./tests/Fixtures/site2.example.com/bower.json",
                "composer" => "./tests/Fixtures/site2.example.com/composer.lock",
                "package" => "./tests/Fixtures/site2.example.com/package-lock.json",
            ]
        ];

        $config = new Config($config);

        $parser = new Parser($transactions, $config);

        $projects = $parser->parse()->getScannedProjects();

        $this->assertCount(2, $projects);
        $this->assertArrayHasKey('name', $projects[0]);
        $this->assertArrayHasKey('files', $projects[0]);
        $this->assertArrayHasKey('name', $projects[0]);

        $this->assertEquals('site1.example.com', $projects[0]['name']);
        $this->assertArrayHasKey('composer', $projects[0]['files']);
        $this->assertArrayHasKey('laravel', $projects[0]['files']['composer']);
        $this->assertEquals('6.0.4', $projects[0]['files']['composer']['laravel']['version']);
        $this->assertArrayHasKey('phpunit', $projects[0]['files']['composer']);

        $this->assertArrayHasKey('package', $projects[0]['files']);
        $this->assertArrayHasKey('react', $projects[0]['files']['package']);
        $this->assertEquals('16.13.1', $projects[0]['files']['package']['react']['version']);

    }

}
