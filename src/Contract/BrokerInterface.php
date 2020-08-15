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
    public function connect(): bool;

    public function send(MessageInterface $message): bool;

    public function receive(): MessageInterface;

    public function disconnect(): bool;
}
