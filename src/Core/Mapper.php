<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core;

use Clivern\Chunk\Contract\MapperInterface;
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Contract\AbstractMessage;
use Clivern\Chunk\Exception\MessageHandlerFailed;
use Clivern\Chunk\Exception\MessageHandlerNotFound;
use Exception;

/**
 * Mapper Class.
 *
 * This class maps the incoming message to the related handler
 */
class Mapper implements MapperInterface
{
    private $handlers = [];

    /**
     * Class Constructor.
     */
    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandler(string $type): bool
    {
        return isset($this->handlers[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $type): MessageHandlerInterface
    {
        if (!$this->hasHandler($type)) {
            throw new MessageHandlerNotFound(sprintf('Error! message handler of type %s not found', $type));
        }

        return $this->handlers[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(MessageHandlerInterface $handler): MapperInterface
    {
        $this->handlers[$handler->getType()] = $handler;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function callHandler(AbstractMessage $message): bool
    {
        try {
            $handler = $this->getHandler($message->getHandlerType());
            $handler->invoke($message);
            $handler->onSuccess();
        } catch (Exception $e) {
            $handler->onFailure();
            throw new MessageHandlerFailed($e->getMessage());
        }

        return true;
    }
}
