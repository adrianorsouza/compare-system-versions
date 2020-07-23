<?php

namespace Adrianorosa\Csv;

/**
 * Class Csv.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-13 19:24
 *
 * @package Adrianorosa\Csv
 */
class Csv
{
    const VERSION = '0.1.0';

    // protected $iterator;

    protected $config = [];

    /**
     * Csv constructor.
     *
     * @param $iterator
     * @param  array $config
     */
    public function __construct($iterator, array $config = [])
    {

        $this['files'] = $iterator;

        // $this->config = $config;
        // $this['latest_versions'] = $config['latest_versions'];
        $this['project'] = function($sv) {


        };

        foreach ($config as $key => $value) {
            $this[$key] = $value;
        }
    }

}
