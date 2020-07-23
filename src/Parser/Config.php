<?php

namespace Adrianorosa\Csv\Parser;

use Symfony\Component\Finder\Finder;

/**
 * Class Config.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-14 15:59
 *
 * @package Adrianorosa\Csv\Parser
 */
class Config
{
    public $system_versions = [];
    public $package_versions = [];
    public $iterator = [];
    public $options = [];

    /**
     * Config constructor.
     *
     * @param  array $config
     */
    public function __construct(array $config = [])
    {
        $this->system_versions = $config['system_versions'];
        $this->package_versions = $config['package_versions'];
        $this->options = $config['options'];
        $this->iterator = $config['iterator'];
    }

    public function getIterator($key = null)
    {
        if (is_null($key)) {
            return $this->iterator;
        }

        return $this->iterator[$key];
    }

    public function getOptions($key = null)
    {
        if (is_null($key)) {
            return $this->options;
        }

        return $this->options[$key];
    }

    public function getSystemVersionsLatest($key = null)
    {
        if (null === $key) {
            return $this->system_versions['latest'];
        }

        return $this->system_versions['latest'][$key] ?? null;
    }

    // public function getSystemVersionsCurrent($key = null)
    // {
    //     if (null === $key) {
    //         return $this->system_versions['current'];
    //     }
    //
    //     return $this->system_versions['current'][$key];
    // }

    public function getPackageVersionsLatest($key = null)
    {
        if (null === $key) {
            return $this->package_versions['latest'];
        }

        return $this->package_versions['latest'][$key] ?? null;
    }



}
