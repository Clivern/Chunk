<?php

declare(strict_types=1);

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Chunk\Core\Broker;

use Clivern\Chunk\Contract\BrokerInterface;
use Clivern\Chunk\Contract\MessageInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * RabbitMQ Class.
 */
class RabbitMQ implements BrokerInterface
{
    /** @var string */
    private $server;

    /** @var int */
    private $port;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var obj */
    private $connection;

    /** @var obj */
    private $channel;

    /** @var string */
    private $queue = 'default';

    /** @var string */
    private $exchange = '';

    /** @var array */
    private $configs = [
        'queue' => [
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'auto_delete' => false,
        ],
        'consumer' => [
            'consumer_tag' => '',
            'no_local' => false,
            'no_ack' => true,
            'exclusive' => false,
            'nowait' => false,
        ],
        'delivery' => [
            // make message persistent, so it is not lost if server crashes or quits
            'delivery_mode' => 2,
        ],
    ];

    /**
     * Class Constructor.
     */
    public function __construct(
        string $server,
        int $port,
        string $username,
        string $password,
        string $queue = 'default',
        string $exchange = '',
        array $configs = []
    ) {
        $this->server = $server;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->queue = $queue;
        $this->exchange = $exchange;

        $this->configs['queue'] = array_merge(
            $this->configs['queue'],
            isset($configs['queue']) ? $configs['queue'] : []
        );

        $this->configs['consumer'] = array_merge(
            $this->configs['consumer'],
            isset($configs['consumer']) ? $configs['consumer'] : []
        );

        $this->configs['delivery'] = array_merge(
            $this->configs['delivery'],
            isset($configs['delivery']) ? $configs['delivery'] : []
        );
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->server,
                $this->port,
                $this->username,
                $this->password
            );
        } catch (\Exception $e) {
            throw new MQConnctionError(sprintf('Error Connecting to Server %s: %s', $this->server, $e->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $this->declareQueue();

        $msg = new AMQPMessage(
            (string) $message,
            $this->configs['delivery']
        );

        $this->channel->basic_publish($msg, $this->exchange, $this->queue);
    }

    /**
     * {@inheritdoc}
     */
    public function receive($callback)
    {
        $this->declareQueue();

        $this->channel->basic_consume(
            $this->queue,
            $this->configs['consumer']['consumer_tag'],
            $this->configs['consumer']['no_local'],
            $this->configs['consumer']['no_ack'],
            $this->configs['consumer']['exclusive'],
            $this->configs['consumer']['nowait'],
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected(): bool
    {
        return $this->connection->isConnected();
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if (!empty($this->channel)) {
            $this->channel->close();
        }
        if (!empty($this->connection)) {
            $this->connection->close();
        }
    }

    /**
     * Declare Queue.
     *
     * @return void
     */
    private function declareQueue()
    {
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(
            $this->queue,
            $this->configs['queue']['passive'],
            $this->configs['queue']['durable'],
            $this->configs['queue']['exclusive'],
            $this->configs['queue']['auto_delete']
        );
    }
}
