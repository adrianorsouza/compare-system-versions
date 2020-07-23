<?php

namespace Adrianorosa\Csv\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateCommand.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-13 19:29
 *
 * @package Adrianorosa\Csv\Console\Command
 */
class UpdateCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('update')
            ->setDescription('Update the data project and system versions.')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output->writeln('::executing::');
        $output->writeln('Parsing ...');
        $output->writeln('<bg=cyan;fg=blue> Updating project ...</>');

        $this->scan();
        $this->scanSystem();
        $this->update();

        $output->writeln('<bg=green;fg=white> Updating complete</>');

        return 1;
    }

}
