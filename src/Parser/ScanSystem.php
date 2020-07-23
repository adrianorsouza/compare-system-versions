<?php

namespace Adrianorosa\Csv\Parser;

use Adrianorosa\Csv\Version;

/**
 * Class ScanSystem.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date   2020-07-14 23:47
 *
 * @package Adrianorosa\Csv\Parser
 */
class ScanSystem implements \JsonSerializable
{

    protected $platform;
    protected $php;
    protected $nginx;
    protected $mysql;

    /**
     * @var \Adrianorosa\Csv\Parser\Config
     */
    protected $config;

    /**
     * ScanSystem constructor.
     *
     * @param  \Adrianorosa\Csv\Parser\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->php = Version::getPhpVersion();
        $this->nginx = Version::getNginxVersion();
        $this->platform = Version::getOsVersion();
    }

    public function toArray()
    {
        return [
            'platform' => [
                'version' => $this->platform,
                'satisfies' => $this->config->getSystemVersionsLatest('platform') === $this->platform ? 'updated' : 'major',
            ],
            'php' => [
                'version' => $this->php,
                'satisfies' => Version::compare($this->config->getSystemVersionsLatest('php'), $this->php),
            ],
            'nginx' => [
                'version' => $this->nginx,
                'satisfies' => Version::compare($this->config->getSystemVersionsLatest('nginx'), $this->nginx),
            ],
            'mysql' => [
                'version' => $this->mysql,
                'satisfies' => Version::compare($this->config->getSystemVersionsLatest('mysql'), $this->mysql),
            ],
        ];
    }

    public function jsonSerialize(): array
    {
        $this->toArray();
    }

}

