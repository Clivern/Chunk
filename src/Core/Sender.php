<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\BrokerInterface;
use Clivern\Chunk\Contract\EventHandlerInterface;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Contract\SenderInterface;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * Sender Class.
 *
 * This class sends messages to the broker
 */
class Sender implements SenderInterface
{
    /** @var BrokerInterface */
    private $broker;

    /** @var EventHandlerInterface */
    private $eventHandler;

    /**
     * Class Constructor.
     */
    public function __construct(
        BrokerInterface $broker,
        EventHandlerInterface $eventHandler
    ) {
        $this->broker = $broker;
        $this->eventHandler = $eventHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        return $this->broker->connect();
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        // Set UUID if not set
        if (empty($message->getUuid())) {
            $message->setUuid(Uuid::uuid4()->toString());
        }

        try {
            $this->broker->send($message);

            if ($this->eventHandler->hasEvent(EventInterface::ON_MESSAGE_SENT_EVENT)) {
                $this->eventHandler->invokeEvent(
                    EventInterface::ON_MESSAGE_SENT_EVENT,
                    $message
                );
            }
        } catch (Exception $e) {
            if ($this->eventHandler->hasEvent(EventInterface::ON_MESSAGE_SEND_FAILURE_EVENT)) {
                $this->eventHandler->invokeEvent(
                    EventInterface::ON_MESSAGE_SEND_FAILURE_EVENT,
                    $message,
                    $e
                );
            } else {
                throw new Exception($e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        return $this->broker->disconnect();
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected(): bool
    {
        return $this->broker->isConnected();
    }
}
