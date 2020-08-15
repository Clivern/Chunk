<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Mapper Interface.
 */
interface MapperInterface
{
    /**
     * Check if mapper has a handler.
     */
    public function hasHandler(string $type): bool;

    /**
     * Get Handler by type.
     */
    public function getHandler(string $type): MessageHandlerInterface;

    /**
     * Add Handler to Mapper.
     */
    public function addHandler(MessageHandlerInterface $handler): self;

    /**
     * Call Handler with a Message.
     */
    public function callHandler(MessageInterface $message): bool;
}
