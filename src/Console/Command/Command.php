<?php

namespace Adrianorosa\Csv\Console\Command;

use Adrianorosa\Csv\Parser;
use Carbon\Carbon;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Command.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-13 19:36
 *
 * @package Adrianorosa\Csv\Console\Command
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var
     */
    protected $app;

    /**
     * @var \Adrianorosa\Csv\Parser\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $transactions = [];

    /**
     * @var array[]
     */
    protected $store = [
        'system' => [],
        'packages' => [],
        'projects' => [],
    ];

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     *
     */
    protected function configure()
    {
        $this->getDefinition()->addArgument(new InputArgument('config', InputArgument::REQUIRED, 'The Configuration'));
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $config = getcwd() . DIRECTORY_SEPARATOR . $input->getArgument('config');

        if (!is_file($config)) {
            throw new \InvalidArgumentException(sprintf('The YAML configuration file "%s" does not exist.', $config));
        }

        $this->output->writeln('::initialize::');
        $this->config = $this->loadConfig($config);
    }

    /**
     *
     */
    public function scan()
    {
        $scan = new Parser\Scan($this->config);
        foreach ($scan->getIterator() as $file) {
            $basename = $file->getRelativePath();
            $dirname = pathinfo($file->getPathname(), PATHINFO_DIRNAME);

            if (!isset($this->transactions[$basename])) {
                // $this->output->writeln('');
                $this->output->writeln('<fg=white> - Scanned '.$basename.'</>');
                $this->transactions[$basename] = [
                    'dirname' => $dirname,
                ];
            }

            if ($file->getFilename() === 'composer.lock') {
                $this->transactions[$basename]['composer'] = $file->getPathname();
            }

            if ($file->getFilename() === 'package-lock.json') {
                $this->transactions[$basename]['package'] = $file->getPathname();
            }

            if ($file->getFilename() === 'bower.json') {
                $this->transactions[$basename]['bower'] = $file->getPathname();
            }
        }

        $parser = new Parser\Parser($this->transactions, $this->config);
        $parser->parse();

        $this->store['projects'] = $parser->getScannedProjects();
    }

    public function scanSystem()
    {
        $this->store['system'] = [
            'latest' => $this->config->getSystemVersionsLatest(),
            'current' => (new Parser\ScanSystem($this->config))->toArray(),
        ];
    }


    /**
     * @return int
     */
    public function update()
    {
        $this->store['packages']['latest'] = $this->config->getPackageVersionsLatest();
        $this->store['updated_at'] = Carbon::now();
        $build_dir = trim($this->config->getOptions('build_dir'), '/');

        $filename = getcwd() . DIRECTORY_SEPARATOR . $build_dir . '/data.json';
        $filesystem = new Filesystem();

        try {
            if (!$filesystem->exists($filename)) {
                $filesystem->mkdir(dirname($filename));
            }

            file_put_contents($filename, json_encode($this->store, JSON_PRETTY_PRINT));

            // Copy the client front-end to the build dir
            $filesystem->mirror(BASE_DIR . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR, $build_dir);

        } catch (\Exception $e) {
            $this->output->writeln("<bg=green;fg=white> {$e->getMessage()}</>");
        }

        return 1;
    }

    /**
     * @param  string $config
     *
     * @return mixed
     */
    private function loadConfig(string $config)
    {
        return new Parser\Config(Yaml::parseFile($config));
    }

}
