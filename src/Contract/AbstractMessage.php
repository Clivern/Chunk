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
     * Convert to string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'id' => $this->id,
            'uuid' => $this->uuid,
            'payload' => $this->payload,
            'type' => $this->type,
        ]);
    }

    /**
     * Load from string.
     *
     * @return void
     */
    public function fromString(string $data): self
    {
        $data = json_decode($data);
        $this->id = $data->id;
        $this->uuid = $data->uuid;
        $this->payload = $data->payload;
        $this->type = $data->type;

        return $this;
    }

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
