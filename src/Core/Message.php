<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\MessageInterface;

/**
 * Message Class.
 */
class Message implements MessageInterface
{
    /** @var string */
    private $id = "";

    /** @var string */
    private $uuid = "";

    /** @var string */
    private $payload = "";

    /** @var string */
    private $type = "";

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function fromString(string $data): MessageInterface
    {
        $data = json_decode($data);
        $this->id = $data->id;
        $this->uuid = $data->uuid;
        $this->payload = $data->payload;
        $this->type = $data->type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(string $id): MessageInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setUuid(string $uuid): MessageInterface
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayload(string $payload): MessageInterface
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function setHandlerType(string $type): MessageInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerType(): string
    {
        return $this->type;
    }
}
