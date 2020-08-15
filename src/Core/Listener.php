<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\ListenerInterface;

/**
 * Listener Class.
 *
 * This class listen to a broker queue for incoming messages
 * and then send the message to mapper
 */
class Listener implements ListenerInterface
{
}
