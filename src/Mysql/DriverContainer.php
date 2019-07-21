<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

trait DriverContainer
{
    /** @var Driver Driver */
    protected $driver;

    /**
     * Set driver for usage
     *
     * @param Driver $driver
     * @return self
     */
    public function setDriver(Driver $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * Get driver
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

}
