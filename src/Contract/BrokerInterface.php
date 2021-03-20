<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Broker Interface.
 */
interface BrokerInterface
{
    /**
     * Establish a Connection.
     */
    public function connect();

    /**
     * Send a message to queue.
     */
    public function send(MessageInterface $message);

    /**
     * Receive messages from a queue.
     *
     * @param mixed $callback
     */
    public function receive($callback);

    /**
     * Check if connection is active.
     */
    public function isConnected(): bool;

    /**
     * Get Broker Configs.
     */
    public function getConfigs(): array;

    /**
     * Disconnect.
     */
    public function disconnect();
}
