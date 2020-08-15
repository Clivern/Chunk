<?php

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Core;

use Clivern\Chunk\Contract\MapperInterface;
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Contract\AbstractMessage;
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
        $mapper = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);

        $handler->expects($this->once())
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $this->assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        $this->assertTrue($mapper->hasHandler('serviceA.processOrderHandler'));
        $this->assertFalse($mapper->hasHandler('serviceA.processPaymentHandler'));
    }

    public function testGetHandler()
    {
        $mapper = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);

        $handler->expects($this->once())
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $this->assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        $this->assertInstanceOf(MessageHandlerInterface::class, $mapper->getHandler('serviceA.processOrderHandler'));

        $this->expectException(MessageHandlerNotFound::class);
        $this->assertInstanceOf(MessageHandlerInterface::class, $mapper->getHandler('serviceA.processPaymentHandler'));
    }

    public function testCallHandler()
    {
        $mapper = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);
        $message = $this->createMock(AbstractMessage::class);

        $message->expects($this->once())
            ->method('getHandlerType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects($this->once())
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects($this->once())
            ->method('invoke')
            ->with($message);

        $handler->expects($this->once())
            ->method('onSuccess');

        $this->assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));
        $this->assertTrue($mapper->callHandler($message));
    }

    public function testCallHandlerFailure()
    {
        $mapper = new Mapper();
        $handler = $this->createMock(MessageHandlerInterface::class);
        $message = $this->createMock(AbstractMessage::class);

        $message->expects($this->once())
            ->method('getHandlerType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects($this->once())
            ->method('getType')
            ->willReturn('serviceA.processOrderHandler');

        $handler->expects($this->once())
            ->method('invoke')
            ->with($message)
            ->will($this->throwException(new \Exception('Failure')));

        $handler->expects($this->once())
            ->method('onFailure');

        $this->assertInstanceOf(MapperInterface::class, $mapper->addHandler($handler));

        $this->expectException(MessageHandlerFailed::class);
        $this->assertTrue($mapper->callHandler($message));
    }
}
