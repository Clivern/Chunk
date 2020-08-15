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
    /**
     * Check if event exists by type.
     */
    public function hasEvent(string $type): bool;

    /**
     * Add an Event.
     *
     * @return EventHandlerInterface
     */
    public function addEvent(EventInterface $event): self;

    /**
     * Invoke an event with a message as parameter.
     *
     * @return void
     */
    public function invokeEvent(string $type, AbstractMessage $message);
}
