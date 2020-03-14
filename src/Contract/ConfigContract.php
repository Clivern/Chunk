<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Config Contract.
 */
interface ConfigContract
{
    /**
     * Set Config Item.
     */
    public function set(string $key, ConfigValueContract $value);

    /**
     * Get Config Item.
     */
    public function get(string $key, ConfigValueContract $default): ConfigValueContract;

    /**
     * Check if Item Exists.
     */
    public function exists(string $key): bool;
}
