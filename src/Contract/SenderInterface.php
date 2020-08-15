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
     * Send Message.
     */
    public function send(AbstractMessage $message): bool;

    /**
     * Disconnect the broker connection.
     */
    public function disconnect(): bool;
}
