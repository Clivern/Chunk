<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * ConfigValue Contract.
 */
interface ConfigValueContract
{
    /**
     * Get Value.
     *
     * @return mixed
     */
    public function value();
}
