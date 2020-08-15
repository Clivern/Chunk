<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Event Interface.
 */
interface EventInterface
{
    const ON_MESSAGE_SENT_EVENT = 'onMessageSentEvent';
    const ON_MESSAGE_RECEIVED_EVENT = 'onMessageReceivedEvent';
    const ON_MESSAGE_HANDLED_EVENT = 'onMessageHandledEvent';
    const ON_MESSAGE_FAILED_EVENT = 'onMessageFailedEvent';

    /**
     * Get Event Type.
     */
    public function getType(): string;

    /**
     * Invoke Event.
     *
     * @return void
     */
    public function invoke(MessageInterface $message);
}
