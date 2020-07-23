<?php
/**
 * @project: compare-system-versions
 * @file   : VersionTest.php
 * @author : Adriano Rosa <https://adrianorosa.com>
 * @date   : 2020-07-09 00:46
 */

use Adrianorosa\Csv\RemoteTag;
use Adrianorosa\Csv\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    // public function testName()
    // {
    //     $laravel = new RemoteTag('https://github.com/laravel/framework');
    //     $react = new RemoteTag('https://github.com/facebook/react');
    //     $phpunit = new RemoteTag('https://github.com/sebastianbergmann/phpunit');
    //     // $mysql = new RemoteTag('https://github.com/mysql/mysql-server');
    //     $grunt = new RemoteTag('https://github.com/gruntjs/grunt');
    //     $nginx = new RemoteTag('https://github.com/nginx/nginx');
    //
    //     $nginx = new RemoteTag('https://github.com/nginx/nginx');
    //
    //     dd(
    //         // $laravel->getVersion(),
    //         // $react->getVersion(),
    //         // $phpunit->getVersion(),
    //         // $mysql->getVersion(),
    //         // $grunt->getVersion(),
    //         // $nginx->getVersion(),
    //         Version::getNginxVersion(),
    //         Version::getPhpVersion(),
    //     );
    // }

    public function testCompareMajor()
    {
        $this->assertEquals('', Version::compare('7.18.0', null));
        $this->assertEquals('', Version::compare('', '7.18.0'));
        $this->assertEquals('', Version::compare('', null));
        $this->assertEquals('major', Version::compare('7.18.0', '6.2.0'));
        $this->assertEquals('minor', Version::compare('7.18.0', '7.2.0'));
        $this->assertEquals('patch', Version::compare('7.18.10', '7.18.9'));
        $this->assertEquals('updated', Version::compare('7.18.10', '8.12.10'));
    }

    public function testCompareMinor()
    {
        $this->assertEquals('minor', Version::compare('16.13.0', '16.10.0'));
        $this->assertEquals('minor', Version::compare('7.18.0', '7.2.0'));
        $this->assertEquals('updated', Version::compare('7.18.10', '7.19.9'));
        $this->assertEquals('updated', Version::compare('7.18.10', '8.12.10'));
    }

    public function testComparePatch()
    {
        $this->assertEquals('updated', Version::compare('16.13.0', '16.16.0'));
        $this->assertEquals('patch', Version::compare('1.2.1', '1.2.0'));
        $this->assertEquals('patch', Version::compare('7.18.10', '7.18.8'));
        $this->assertEquals('patch', Version::compare('7.18.100', '7.18.12'));
    }

}
