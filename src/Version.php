<?php

namespace Adrianorosa\Csv;

/**
 * Class Version.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-09 00:02
 *
 * @package Adrianorosa\Csv
 */
class Version
{
    public static function compare($version1, $version2): string
    {
        if (substr_count($version1, '.') +1 !== 3 || substr_count($version2, '.') +1 !== 3) {
            return '';
        }

        [$v1Major, $v1Minor, $v1Patch] = explode('.', $version1);
        [$v2Major, $v2Minor, $v2Patch] = explode('.', $version2);

        if ($v1Major > $v2Major) {
            return 'major';
        }

        if ($v1Major === $v2Major && $v1Minor > $v2Minor) {
            return 'minor';
        }

        if ($v1Minor === $v2Minor && $v1Patch > $v2Patch) {
            return 'patch';
        }

        return 'updated';
    }

    public static function getOsVersion()
    {
        $uname = strtoupper(php_uname('s'));

        if ($uname === 'LINUX') {
            $cmd = escapeshellcmd('cat /etc/os-release');
            $result = static::execCommand($cmd);
            if ($result) {
                preg_match_all('@([A-Z]+)=\"(.*)\"\n@', $result, $matches);

                if (isset($matches[2])) {
                    return "{$matches[2][0]} {$matches[2][1]}";
                }
            }
        }

        return php_uname('s');
    }

    public static function getNginxVersion()
    {
        $result = static::execCommand('nginx -v');

        return str_replace('nginx version: nginx/', '', $result);
    }

    public static function getPhpVersion()
    {
        $result = static::execCommand('php --version');

        preg_match('/PHP\s[0-9]{1,}.[0-9]{1,}.[0-9]{1,}./', $result, $matches);

        return str_replace(['PHP', ' ', '-'], '', $matches[0]);
    }

    private static function execCommand($cmd)
    {
        $process = proc_open(
            $cmd,
            [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            '/tmp'
        );

        if (!is_resource($process)) {
            return false;
        }

        $result = trim(stream_get_contents($pipes[1]) . "\n" . stream_get_contents($pipes[2]));


        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            return false;
        }

        return $result;
    }
}
