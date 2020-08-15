<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\AbstractMessage;
use Clivern\Chunk\Contract\BrokerInterface;
use Clivern\Chunk\Contract\EventHandlerInterface;
use Clivern\Chunk\Contract\SenderInterface;

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
    public function send(AbstractMessage $message): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect(): bool
    {
        return true;
    }
}
