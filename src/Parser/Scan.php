<?php

namespace Adrianorosa\Csv\Parser;

use Symfony\Component\Finder\Finder;

/**
 * Class Scan.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-14 16:16
 *
 * @package Adrianorosa\Csv\Parser
 */
class Scan
{
    protected $finder;

    /**
     * Scan constructor.
     *
     * @param  \Adrianorosa\Csv\Parser\Config $config
     */
    public function __construct(Config $config)
    {
        $this->finder = (new Finder())
            ->files()
            ->exclude($config->getIterator('exclude'))
            ->in($config->getIterator('dir'))
            ->name($config->getIterator('packages'))
            ->depth('< 2')
            ->sortByName();
    }

    public function getIterator()
    {
        return $this->finder;
    }
}
