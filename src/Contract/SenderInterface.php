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
    public function send(MessageInterface $message): bool;

    public function disconnect(): bool;
}
