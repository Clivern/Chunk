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
    public function invoke();

    public function onSuccess();

    public function onFailure();

    public function getType(): string;
}
