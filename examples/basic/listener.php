<?php

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

include_once __DIR__.'/vendor/autoload.php';

use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Contract\MessageInterface;
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
    public function invoke(MessageInterface $message, $exception = null)
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
    public function invoke(MessageInterface $message, $exception = null)
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
    public function invoke(MessageInterface $message, $exception = null)
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
    public function invoke(MessageInterface $message): MessageHandlerInterface
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

$server = '127.0.0.1';
$port = 5672;
$username = 'guest';
$password = 'guest';
$configs = [
    'consumer' => ['no_ack' => true],

    'vhost' => '/',

    'queue' => ['name' => 'serviceA_events_orders'],
    'exchange' => ['name' => 'serviceA_events', 'type' => RabbitMQ::DIRECT_EXCHANGE],
    'routing' => ['key' => 'serviceA_events_orders'],
];

$broker = new RabbitMQ(
    $server,
    $port,
    $username,
    $password,
    $configs
);

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
