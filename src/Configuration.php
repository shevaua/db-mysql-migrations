<?php

namespace Shevaua\DB\Mysql\Migrations;

trait Configuration
{
    /** @var Config Configuration */
    protected $config;

    /**
     * Set config
     *
     * @param Config $config
     * @return self
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;
        return $this;
    }

}
