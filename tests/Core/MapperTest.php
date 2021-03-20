<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Core;

use Clivern\Chunk\Contract\MapperInterface;
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Core\Mapper;
use Clivern\Chunk\Exception\MessageHandlerFailed;
use Clivern\Chunk\Exception\MessageHandlerNotFound;
use PHPUnit\Framework\TestCase;

/**
 * MapperTest Class Test.
 */
class MapperTest extends TestCase
{
    public function testHasHandler()
    {
        $mapper  = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);

        $handler->expects(self::exactly(2))
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        self::assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        self::assertTrue($mapper->hasHandler('serviceA.processOrderHandler'));
        self::assertFalse($mapper->hasHandler('serviceA.processPaymentHandler'));
    }

    public function testGetHandler()
    {
        $mapper  = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);

        $handler->expects(self::exactly(2))
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        self::assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        self::assertInstanceOf(MessageHandlerInterface::class, $mapper->getHandler('serviceA.processOrderHandler'));

        $this->expectException(MessageHandlerNotFound::class);
        self::assertInstanceOf(MessageHandlerInterface::class, $mapper->getHandler('serviceA.processPaymentHandler'));
    }

    public function testCallHandler()
    {
        $mapper  = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);
        $message = $this->createMock(MessageInterface::class);

        $message->expects(self::once())
            ->method('getHandlerType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects(self::exactly(2))
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects(self::once())
            ->method('invoke')
            ->with($message);

        $handler->expects(self::once())
            ->method('onSuccess');

        self::assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        self::assertTrue($mapper->callHandler($message));
    }

    public function testCallHandlerFailure()
    {
        $mapper  = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);
        $message = $this->createMock(MessageInterface::class);

        $message->expects(self::once())
            ->method('getHandlerType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects(self::exactly(2))
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects(self::once())
            ->method('invoke')
            ->with($message)
            ->will(self::throwException(new \Exception('Failure')));

        $handler->expects(self::once())
            ->method('onFailure');

        self::assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));

        $this->expectException(MessageHandlerFailed::class);
        self::assertTrue($mapper->callHandler($message));
    }
}
