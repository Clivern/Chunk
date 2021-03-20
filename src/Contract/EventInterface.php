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
    public const ON_MESSAGE_SENT_EVENT         = 'onMessageSentEvent';
    public const ON_MESSAGE_RECEIVED_EVENT     = 'onMessageReceivedEvent';
    public const ON_MESSAGE_HANDLED_EVENT      = 'onMessageHandledEvent';
    public const ON_MESSAGE_FAILED_EVENT       = 'onMessageFailedEvent';
    public const ON_MESSAGE_SEND_FAILURE_EVENT = 'onMessageSendFailureEvent';

    /**
     * Get Event Type.
     */
    public function getType(): string;

    /**
     * Invoke Event.
     *
     * @param null|mixed $exception
     */
    public function invoke(MessageInterface $message, $exception = null);
}
