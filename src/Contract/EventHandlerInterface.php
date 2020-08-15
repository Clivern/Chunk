<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Event Handler Interface.
 */
interface EventHandlerInterface
{
    public function addEvent(EventInterface $event): bool;

    public function invokeEvents(string $type);
}
