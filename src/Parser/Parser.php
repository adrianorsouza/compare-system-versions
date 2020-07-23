<?php

namespace Adrianorosa\Csv\Parser;

use Adrianorosa\Csv\Version;

/**
 * Class Parser.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-04 23:45
 *
 * @package Adrianorosa\Csv\Parser
 */
class Parser
{

    protected $config;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $result = [];

    /**
     * Parser constructor.
     *
     * @param  array $data
     * @param  \Adrianorosa\Csv\Parser\Config $config
     */
    public function __construct(array $data, Config $config)
    {
        $this->data = $data;
        $this->config = $config;
    }

    /**
     *
     */
    public function parse()
    {
        foreach ($this->data as $path => $items) {
            $result = [
                'name' => $path,
                // 'path' => $items['dirname'],
                'files' => [
                    // 'composer' => null,
                    // 'package' => null,
                    // 'bower' => null,
                ],
            ];

            foreach ($items as $key => $value) {
                if ($key === 'dirname') {
                    continue;
                }

                $content = json_decode(file_get_contents($value), true);
                switch ($key) {
                    case 'composer':
                        $result['files']['composer'] = $this->getComposerPackagesVersion($content);
                        break;

                    case 'package':
                        if ($gruntVersion = $this->getNPMPackagesVersion($content, 'grunt')) {
                            $result['files']['package']['grunt'] = [
                                'version' => $gruntVersion,
                                'satisfies' => Version::compare($this->config->getPackageVersionsLatest('grunt'), $gruntVersion)
                            ];
                        };
                        if ($reactVersion = $this->getNPMPackagesVersion($content, 'react')) {
                            $result['files']['package']['react'] = [
                                'version' => $reactVersion,
                                'satisfies' => Version::compare($this->config->getPackageVersionsLatest('react'), $reactVersion),
                            ];
                        };
                        break;

                    case 'bower':
                        if ($jquery = $this->getBowerPackageVersion($content, 'jquery')) {
                            $result['files']['bower']['jquery']['version'] = $jquery;
                        };
                        if ($bootstrap = $this->getBowerPackageVersion($content, 'bootstrap-sass')) {
                            $result['files']['bower']['bootstrap-sass']['version'] = $bootstrap;
                        };
                        break;
                }
            }

            $this->result[] = $result;
        }

        return $this;
    }

    /**
     * @param  array $content
     *
     * @return array|null
     */
    protected function getComposerPackagesVersion(array $content): ?array
    {
        $fn = function ($p, $v) {
            if ($p['name'] === 'phpunit/phpunit') {
                return [
                    'version' => $v,
                    'satisfies' => Version::compare($this->config->getPackageVersionsLatest('phpunit'), $v)
                ];
            }
            return null;
        };

        $composer = [];
        // Look for dependencies within packages list
        foreach ($content['packages'] as $package) {
            $v = $this->formatVersion($package['version']);
            if ($package['name'] === 'laravel/framework') {
                $composer['laravel'] = [
                    'version' => $v,
                    'satisfies' => Version::compare($this->config->getPackageVersionsLatest('laravel'), $v)
                ];
                break;
            }

            if ($phpunit = $fn($package, $v)) {
                $composer['phpunit'] = $phpunit;
                break;
            }
        }

        // Look for dependencies within dev packages list
        foreach ($content['packages-dev'] as $packages_dev) {
            $v = $this->formatVersion($packages_dev['version']);
            if ($phpunit = $fn($packages_dev, $v)) {
                $composer['phpunit'] = $phpunit;
                break;
            }
        }

        return $composer;
    }

    /**
     * @param array $content
     * @param $packageName
     *
     * @return string|null
     */
    protected function getNPMPackagesVersion(array $content, $packageName): ?string
    {
        $version = null;
        foreach ($content['dependencies'] as $package => $value) {
            if ($package === $packageName) {
                $version = $this->formatVersion($value['version']);
                break;
            }
        }

        return $version;
    }

    /**
     * @param array $content
     * @param $packageName
     *
     * @return string|null
     */
    protected function getBowerPackageVersion(array $content, $packageName): ?string
    {
        $version = null;
        foreach ($content['devDependencies'] as $package => $installedVersion) {
            if ($package === $packageName) {
                $version = $this->formatVersion($installedVersion);
                break;
            }
        }

        return $version;
    }

    /**
     * @param $version
     *
     * @return string|string[]
     */
    protected function formatVersion($version)
    {
        return str_replace(['v', '~', '^', ' ', '>='], '', $version);
    }

    /**
     * @return array
     */
    public function getScannedProjects()
    {
        return $this->result;
    }
}
