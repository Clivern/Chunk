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
abstract class AbstractMessage
{
    /** @var string */
    private $id;

    /** @var string */
    private $uuid;

    /** @var string */
    private $payload;

    /** @var string */
    private $type;

    /**
     * Set Message ID.
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get Message ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set Message UUID.
     *
     * @return AbstractMessage
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get UUID.
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set Payload.
     *
     * @return AbstractMessage
     */
    public function setPayload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get Payload.
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * Set Handler Type.
     *
     * @return AbstractMessage
     */
    public function setHandlerType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Handler Type.
     */
    public function getHandlerType(): string
    {
        return $this->type;
    }
}
