<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Message Handler Interface.
 */
interface MessageHandlerInterface
{
    /**
     * Invoke Handler.
     *
     * @return MessageHandlerInterface
     */
    public function invoke(MessageInterface $message): self;

    /**
     * onSuccess Event.
     *
     * @return void
     */
    public function onSuccess();

    /**
     * onFailure Event.
     *
     * @return void
     */
    public function onFailure();

    /**
     * Handler Type.
     */
    public function getType(): string;
}
