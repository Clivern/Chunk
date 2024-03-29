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
use Clivern\Chunk\Contract\ListenerInterface;
use Clivern\Chunk\Contract\MapperInterface;
use Clivern\Chunk\Contract\MessageInterface;

/**
 * Listener Class.
 *
 * This class listen to a broker queue for incoming messages
 * and then send the message to mapper
 */
class Listener implements ListenerInterface
{
    /** @var BrokerInterface */
    private $broker;

    /** @var EventHandlerInterface */
    private $eventHandler;

    /** @var MapperInterface */
    private $mapper;

    /** @var MapperInterface */
    private $messageObj;

    /**
     * Class Constructor.
     *
     * @param null|mixed $messageObj
     */
    public function __construct(
        BrokerInterface $broker,
        EventHandlerInterface $eventHandler,
        MapperInterface $mapper,
        $messageObj = null
    ) {
        $this->broker       = $broker;
        $this->eventHandler = $eventHandler;
        $this->mapper       = $mapper;

        if (!empty($messageObj) && $messageObj instanceof MessageInterface) {
            $this->messageObj = $messageObj;
        } else {
            $this->messageObj = new Message();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listen(): bool
    {
        return $this->broker->receive([
            $this,
            'callback',
        ]);
    }

    /**
     * Message callback.
     *
     * @param object $message
     */
    public function callback($message)
    {
        $this->messageObj->fromString($message->body);

        if ($this->eventHandler->hasEvent(EventInterface::ON_MESSAGE_RECEIVED_EVENT)) {
            $this->eventHandler->invokeEvent(
                EventInterface::ON_MESSAGE_RECEIVED_EVENT,
                $this->messageObj
            );
        }

        try {
            $this->mapper->callHandler($this->messageObj);

            if ($this->eventHandler->hasEvent(EventInterface::ON_MESSAGE_HANDLED_EVENT)) {
                $this->eventHandler->invokeEvent(
                    EventInterface::ON_MESSAGE_HANDLED_EVENT,
                    $this->messageObj
                );
            }
        } catch (\Exception $e) {
            if ($this->eventHandler->hasEvent(EventInterface::ON_MESSAGE_FAILED_EVENT)) {
                $this->eventHandler->invokeEvent(
                    EventInterface::ON_MESSAGE_FAILED_EVENT,
                    $this->messageObj,
                    $e
                );
            }
        }

        // if message ack is enabled
        if (!$this->broker->getConfigs()['consumer']['no_ack']) {
            $message->delivery_info['channel']->basic_ack(
                $message->delivery_info['delivery_tag']
            );
        }
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
    public function disconnect(): bool
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
