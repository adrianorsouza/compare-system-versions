<?php

namespace Adrianorosa\Csv\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ScanCommand.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-14 16:18
 *
 * @package Adrianorosa\Csv\Console\Command
 */
class ScanCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('scan')
            ->setDescription('Scan and display files and system versions.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<bg=cyan;fg=blue> Scanning project ...</>');

        $this->scanSystem();
        $this->scan();

        return 0;
    }

}
