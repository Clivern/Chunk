<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\EventHandlerInterface;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Exception\EventHandlerNotFound;
use Clivern\Chunk\Exception\HandlerTypeNotDefined;

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
        if (empty($event->getType())) {
            throw new HandlerTypeNotDefined(sprintf('Handler %s missing the type', \get_class($event)));
        }

        $this->events[$event->getType()] = $event;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function invokeEvent(string $type, MessageInterface $message)
    {
        if (!$this->hasEvent($type)) {
            throw new EventHandlerNotFound(sprintf('Error! event handler of type %s not found', $type));
        }

        return $this->events[$type]->invoke($message);
    }
}
