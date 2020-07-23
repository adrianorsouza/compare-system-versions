<?php

namespace Adrianorosa\Csv\Console;

use Adrianorosa\Csv\Console\Command\ScanCommand;
use Adrianorosa\Csv\Console\Command\UpdateCommand;
use Symfony\Component\Console\Application as BaseApplication;
/**
 * Class Application.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-13 19:21
 *
 * @package Adrianorosa\Csv\Console
 */
class Application extends BaseApplication
{

    const VERSION = '0.1.0';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('Csv', static::VERSION);

        $this->add(new ScanCommand());
        $this->add(new UpdateCommand());
    }
}
