<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\EventHandlerInterface;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\AbstractMessage;
use Clivern\Chunk\Exception\EventHandlerNotFound;

/**
 * EventHandler Class.
 */
class EventHandler implements EventHandlerInterface
{
    private $events = [];

    /**
     * Class Constructor.
     */
    public function __construct(array $events = [])
    {
        $this->events = $events;
    }

    /**
     * {@inheritdoc}
     */
    public function hasEvent(string $type): bool
    {
        return isset($this->events[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function addEvent(EventInterface $event): EventHandlerInterface
    {
        $this->events[$event->getType()] = $event;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function invokeEvent(string $type, AbstractMessage $message)
    {
        if (!$this->hasEvent($type)) {
            throw new EventHandlerNotFound(sprintf('Error! event handler of type %s not found', $type));
        }

        return $this->events[$type]->invoke($message);
    }
}
