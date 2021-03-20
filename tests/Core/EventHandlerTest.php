<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Core;

use Clivern\Chunk\Contract\EventHandlerInterface;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Core\EventHandler;
use Clivern\Chunk\Exception\EventHandlerNotFound;
use PHPUnit\Framework\TestCase;

/**
 * EventHandler Class Test.
 */
class EventHandlerTest extends TestCase
{
    public function testHasEvent()
    {
        $eventHandler = new EventHandler();
        $event        = $this->createMock(EventInterface::class);

        $event->expects(self::exactly(2))
            ->method('getType')
            ->willReturn(EventInterface::ON_MESSAGE_SENT_EVENT);

        self::assertInstanceOf(EventHandlerInterface::class, $eventHandler->addEvent($event));
        self::assertTrue($eventHandler->hasEvent(EventInterface::ON_MESSAGE_SENT_EVENT));
        self::assertFalse($eventHandler->hasEvent(EventInterface::ON_MESSAGE_RECEIVED_EVENT));
    }

    public function testAddEvent()
    {
        $eventHandler = new EventHandler();
        $event        = $this->createMock(EventInterface::class);

        $event->expects(self::exactly(2))
            ->method('getType')
            ->willReturn(EventInterface::ON_MESSAGE_SENT_EVENT);

        self::assertInstanceOf(EventHandlerInterface::class, $eventHandler->addEvent($event));
        self::assertTrue($eventHandler->hasEvent(EventInterface::ON_MESSAGE_SENT_EVENT));
        self::assertFalse($eventHandler->hasEvent(EventInterface::ON_MESSAGE_RECEIVED_EVENT));
    }

    public function testInvokeEvent()
    {
        $eventHandler = new EventHandler();
        $event        = $this->createMock(EventInterface::class);
        $message      = $this->createMock(MessageInterface::class);

        $event->expects(self::exactly(2))
            ->method('getType')
            ->willReturn(EventInterface::ON_MESSAGE_SENT_EVENT);

        $event->expects(self::once())
            ->method('invoke')
            ->with($message)
            ->willReturn(true);

        self::assertInstanceOf(EventHandlerInterface::class, $eventHandler->addEvent($event));
        self::assertTrue($eventHandler->invokeEvent(EventInterface::ON_MESSAGE_SENT_EVENT, $message));

        // If event not exist
        $this->expectException(EventHandlerNotFound::class);
        self::assertFalse($eventHandler->invokeEvent(EventInterface::ON_MESSAGE_RECEIVED_EVENT, $message));
    }
}
