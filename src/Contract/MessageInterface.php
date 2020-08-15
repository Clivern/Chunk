<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Contract;

/**
 * Message Interface.
 */
interface MessageInterface
{
    /**
     * Set Message ID.
     */
    public function setId(string $id): self;

    /**
     * Get Message ID.
     */
    public function getId(): string;

    /**
     * Set Message UUID.
     *
     * @return MessageInterface
     */
    public function setUuid(string $uuid): self;

    /**
     * Get UUID.
     */
    public function getUuid(): string;

    /**
     * Get Payload.
     */
    public function getPayload(): string;

    /**
     * Set Payload.
     *
     * @return MessageInterface
     */
    public function setPayload(string $payload): self;

    /**
     * Set Handler Type.
     *
     * @return MessageInterface
     */
    public function setHandlerType(string $type): self;

    /**
     * Get Handler Type.
     */
    public function getHandlerType(): string;
}
