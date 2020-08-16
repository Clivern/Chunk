<?php

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

include_once __DIR__.'/vendor/autoload.php';

use Clivern\Chunk\Contract\AbstractMessage;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Core\Broker\RabbitMQ;
use Clivern\Chunk\Core\EventHandler;
use Clivern\Chunk\Core\Listener;
use Clivern\Chunk\Core\Mapper;

////////////////////////
// Create Event Handlers
////////////////////////
class MessageReceivedEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_RECEIVED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(AbstractMessage $message)
    {
        var_dump(sprintf('Message Received Event: %s', (string) $message));
    }
}

class MessageFailedEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_FAILED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(AbstractMessage $message)
    {
        var_dump(sprintf('Message Failed Event: %s', (string) $message));
    }
}

class MessageHandledEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_HANDLED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(AbstractMessage $message)
    {
        var_dump(sprintf('Message Handled Event: %s', (string) $message));
    }
}

//////////////////////////
// Create Message Handlers
//////////////////////////
class ProcessOrderMessageHandler implements MessageHandlerInterface
{
    /**
     * Invoke Handler.
     */
    public function invoke(AbstractMessage $message): MessageHandlerInterface
    {
        var_dump(sprintf('Process Message: %s', (string) $message));

        return $this;
    }

    /**
     * onSuccess Event.
     *
     * @return void
     */
    public function onSuccess()
    {
        var_dump('Operation Succeeded');
    }

    /**
     * onFailure Event.
     *
     * @return void
     */
    public function onFailure()
    {
        var_dump('Operation Failed');
    }

    /**
     * Handler Type.
     */
    public function getType(): string
    {
        return 'serviceA.processOrder';
    }
}

$broker = new RabbitMQ('127.0.0.1', 5672, 'guest', 'guest');

$eventHandler = new EventHandler();
$eventHandler->addEvent(new MessageReceivedEvent())
            ->addEvent(new MessageFailedEvent())
            ->addEvent(new MessageHandledEvent());

$mapper = new Mapper();
$mapper->addHandler(new ProcessOrderMessageHandler());

$listener = new Listener($broker, $eventHandler, $mapper);

$listener->connect();

$listener->listen();

$listener->disconnect();
