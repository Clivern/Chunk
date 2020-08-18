<?php

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

include_once __DIR__.'/vendor/autoload.php';

use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Core\Broker\RabbitMQ;
use Clivern\Chunk\Core\EventHandler;
use Clivern\Chunk\Core\Message;
use Clivern\Chunk\Core\Sender;

////////////////////////
// Create Event Handlers
////////////////////////
class MessageSentEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_SENT_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Sent Event: %s', (string) $message));
    }
}

class MessageSendFailureEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_SEND_FAILURE_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Send Failure Event: %s', (string) $message));
        var_dump(sprintf('Error raised: %s', $exception->getMessage()));
    }
}

$server = '127.0.0.1';
$port = 5672;
$username = 'guest';
$password = 'guest';
$configs = [
    'consumer' => ['no_ack' => true],

    'queue' => ['name' => 'default'],
    'vhost' => '/',
    'routing' => ['key' => 'default'],
    'exchange' => ['name' => '', 'type' => 'direct'],
];

$broker = new RabbitMQ(
    $server,
    $port,
    $username,
    $password,
    $configs
);

$eventHandler = new EventHandler();
$eventHandler->addEvent(new MessageSentEvent())
             ->addEvent(new MessageSendFailureEvent());

$sender = new Sender($broker, $eventHandler);

$sender->connect();

$message = new Message();
$message->setId(1)
        ->setPayload('something')
        ->setHandlerType('serviceA.processOrder');

$sender->send($message);
$sender->disconnect();
