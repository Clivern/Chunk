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
     * Load from string.
     */
    public function fromString(string $data): self;

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
     */
    public function setUuid(string $uuid): self;

    /**
     * Get UUID.
     */
    public function getUuid(): string;

    /**
     * Set Payload.
     */
    public function setPayload(string $payload): self;

    /**
     * Get Payload.
     */
    public function getPayload(): string;

    /**
     * Set Handler Type.
     */
    public function setHandlerType(string $type): self;

    /**
     * Get Handler Type.
     */
    public function getHandlerType(): string;
}
