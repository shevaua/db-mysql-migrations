<?php

namespace Shevaua\DB\Mysql\Migrations\FileSystem;

use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\Configuration;

class FSDriver
{
    use Configuration;

    const IGNORED_ITEMS = ['.', '..'];

    /** @var array List of folders for scan */
    protected $queue = [];

    /** @var array Map of migrations */
    protected $migrations = [];

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
    }

    /**
     * Scans for migratins an return full list of them
     *
     * @return array
     */
    public function getMigrations(): array
    {
        $this->queue[] = $this->config->getFolder();

        while ($this->queue) {
            $this->scanNextPath();
        }

        $result = $this->migrations;

        $this->migrations = [];

        return $result;
    }

    /**
     * Scan next path from the queue for migrations
     *
     * @return void
     */
    protected function scanNextPath(): void
    {
        $path = array_shift($this->queue);

        if (is_link($path) or !is_dir($path)) {
            return;
        }

        $items = scandir($path);

        foreach ($items as $item) {
            if (in_array($item, self::IGNORED_ITEMS)) {
                continue;
            }

            $fullPath = $path . '/' . $item;

            $relativePath = str_replace(
                $this->config->getFolder() . '/',
                '',
                $fullPath
            );

            if (is_link($fullPath)) {
                continue;
            }

            if ($this->isIgnored($relativePath)) {
                continue;
            }

            if (is_dir($fullPath)) {
                $this->queue[] = $fullPath;
                continue;
            }

            if (is_file($fullPath)) {
                $this->migrations[] = $relativePath;
                continue;
            }
        }

    }

    /**
     * Checks whether provided path is ignored within config or not
     *
     * @param string $path
     * @return boolean
     */
    protected function isIgnored(string $path): bool
    {
        /** @var array */
        $ignored = $this->config->getIgnoreList();

        return in_array($path, $ignored);
    }

}
