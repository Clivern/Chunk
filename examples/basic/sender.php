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

$broker = new RabbitMQ('127.0.0.1', 5672, 'guest', 'guest');

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
