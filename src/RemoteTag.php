<?php

namespace Adrianorosa\Csv;

/**
 * Class RemoteTag.
 *
 * TODO RENAME TO GetVersion or similar
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-09 22:34
 *
 * @package Adrianorosa\Csv
 */
class RemoteTag
{
//    git ls-remote --tags --sort="v:refname" --refs https://github.com/laravel/framework/ | tail -n 1 | awk '{print $2}' | awk -F"/" '{print $3}'

    /**
     * @var
     */
    protected $repository;

    /**
     * RemoteTag constructor.
     *
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return bool|string
     */
    public function getVersion()
    {
        return $this->fetchRemoteVersion();
    }

    public function getNginxVersion()
    {
        $result = $this->execCommand('nginx --version');

        return $result;
    }

    /**
     * @return bool|string
     */
    protected function fetchRemoteVersion()
    {
        // Since: git v2.18.0 has a built-in sort
        // $cmd = "git ls-remote --tags --sort=\"v:refname\" --refs {$this->repository} | tail -n 1 | awk '{print $2}' | awk -F\"/\" '{print $3}'";

        // Before: git v2.17.0 we need to use pipe to system sort
        $cmd = "git ls-remote --tags --refs {$this->repository} | sort -t '/' -k 3 -V | tail -n 1 | awk '{print $2}' | awk -F\"/\" '{print $3}'";

        $result = $this->execCommand($cmd);

        return $this->normalizeVersion($result);
    }

    protected function normalizeVersion($version)
    {
        return preg_replace('/[a-z\-]/', '', $version);
    }

    public static function saveJson()
    {
        $file = new File('versions_packages.json');
        // dd($file->read()->toArray());
        // $data = file_get_contents(__DIR__ . '/../versions_packages.json');
        //
        // dd($data);
        //
        // // if (!$data['updated_at'] || Carbon::now()->subDay()->lessThan(Carbon::parse($data['updated_at']))) {
        // //     dd
        // // }
        //
        // $laravel = new RemoteTag('https://github.com/laravel/framework');
        // $react = new RemoteTag('https://github.com/facebook/react');
        // $phpunit = new RemoteTag('https://github.com/sebastianbergmann/phpunit');
        // $grunt = new RemoteTag('https://github.com/gruntjs/grunt');
        // // $mysql = new RemoteTag('https://github.com/mysql/mysql-server');
        // // $nginx = new RemoteTag('https://github.com/nginx/nginx');
        //
        //
        // $json = json_decode($data, true);
        //
        // $json['latest'] = [
        //     'laravel' => $laravel->getVersion(),
        //     'react' => $react->getVersion(),
        //     'phpunit' => $phpunit->getVersion(),
        //     'grunt' => $grunt->getVersion(),
        // ];
        //
        // $json['updated_at'] = Carbon::now();
        //
        // // dd($json);
    }

    private function execCommand($cmd)
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

        $result = trim(stream_get_contents($pipes[1]));

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            return false;
        }

        return $result;
    }
}
