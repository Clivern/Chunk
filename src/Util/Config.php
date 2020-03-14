<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Util;

use Clivern\Chunk\Contract\ConfigContract;
use Clivern\Chunk\Contract\ConfigValueContract;

/**
 * Config Class.
 */
class Config implements ConfigContract
{
    private $configs = [];

    /**
     * Set Config Item.
     */
    public function set(string $key, ConfigValueContract $value)
    {
        $this->configs[$key] = $value;
    }

    /**
     * Get Config Item.
     */
    public function get(string $key, ConfigValueContract $default): ConfigValueContract
    {
        if ($this->exists($key)) {
            return $this->configs[$key];
        }

        return $default;
    }

    /**
     * Check if Item Exists.
     */
    public function exists(string $key): bool
    {
        return (isset($this->configs[$key])) ? true : false;
    }
}
