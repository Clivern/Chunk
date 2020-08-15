<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Config Interface.
 */
interface ConfigInterface
{
    /**
     * Set Config Item.
     *
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * Get Config Item.
     *
     * @param mixed $default
     */
    public function get(string $key, $default);

    /**
     * Check if Item Exists.
     */
    public function exists(string $key): bool;
}
