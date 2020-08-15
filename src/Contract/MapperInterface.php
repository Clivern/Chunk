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
    public function hasHandler(string $type): bool;

    public function getHandler(string $type): ?MessageHandlerInterface;

    public function addHandler(MessageHandlerInterface $handler): bool;

    public function callHandler(string $type);
}
