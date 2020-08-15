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
    public function setId(string $id): self;

    public function getId(): string;

    public function setUuid(string $uuid): self;

    public function getUuid(): string;

    public function getPayload(): string;

    public function setPayload(string $payload): self;

    public function setHandlerType(string $type): self;

    public function getHandlerType(): string;
}
