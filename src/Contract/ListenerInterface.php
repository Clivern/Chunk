<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Listener Interface.
 */
interface ListenerInterface
{
    /**
     * Listen to Broker queue.
     */
    public function listen(): bool;

    /**
     * Connect to Broker.
     */
    public function connect();

    /**
     * Disconnect.
     */
    public function disconnect(): bool;

    /**
     * Check if Connection Exists.
     */
    public function isConnected(): bool;
}
