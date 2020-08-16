<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Sender Interface.
 */
interface SenderInterface
{
    /**
     * Connect to Broker.
     */
    public function connect();

    /**
     * Send a Message to a Queue.
     */
    public function send(AbstractMessage $message);

    /**
     * Disconnect.
     */
    public function disconnect();

    /**
     * Check if Connection Exists.
     */
    public function isConnected(): bool;
}
