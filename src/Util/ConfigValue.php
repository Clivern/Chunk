<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Util;

use Clivern\Chunk\Contract\ConfigValueContract;

/**
 * Config Value Class.
 */
class ConfigValue implements ConfigValueContract
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * Class Constructor.
     *
     * @param null|mixed $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get Value.
     */
    public function value()
    {
        return $this->value;
    }
}
