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
    public const DIRECT_EXCHANGE  = 'direct';
    public const FANOUT_EXCHANGE  = 'fanout';
    public const TOPIC_EXCHANGE   = 'topic';
    public const HEADERS_EXCHANGE = 'headers';

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

    /** @var array */
    private $configs = [
        'vhost' => '/',

        'queue' => [
            'name'        => '',
            'passive'     => false,
            'durable'     => true,
            'exclusive'   => false,
            'auto_delete' => false,
        ],

        'consumer' => [
            'consumer_tag' => '',
            'no_local'     => false,
            'no_ack'       => true,
            'exclusive'    => false,
            'nowait'       => false,
        ],

        'delivery' => [
            // make message persistent, so it is not lost if server crashes or quits
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ],

        'exchange' => [
            'name'        => '',
            'type'        => self::DIRECT_EXCHANGE,
            'passive'     => false,
            'durable'     => false,
            'auto_delete' => true,
            'internal'    => false,
            'nowait'      => false,
        ],

        'routing' => [
            'key'    => [''],
            'nowait' => false,
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
        array $configs = []
    ) {
        $this->server   = $server;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;

        $this->configs['queue'] = array_merge(
            $this->configs['queue'],
            $configs['queue'] ?? []
        );

        $this->configs['consumer'] = array_merge(
            $this->configs['consumer'],
            $configs['consumer'] ?? []
        );

        $this->configs['exchange'] = array_merge(
            $this->configs['exchange'],
            $configs['exchange'] ?? []
        );

        $this->configs['routing'] = array_merge(
            $this->configs['routing'],
            $configs['routing'] ?? []
        );

        $this->configs['delivery'] = array_merge(
            $this->configs['delivery'],
            $configs['delivery'] ?? []
        );

        $this->configs['vhost'] = (isset($configs['vhost']))
            ? $configs['vhost'] : $this->configs['vhost'];
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
                $this->password,
                $this->configs['vhost']
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
        $this->declare();

        $msg = new AMQPMessage(
            (string) $message,
            $this->configs['delivery']
        );

        $key = (isset($this->configs['routing']['key'][0])) ? $this->configs['routing']['key'][0] : '';

        $this->channel->basic_publish(
            $msg,
            $this->configs['exchange']['name'],
            $key
        );
    }

    /**
     * {@inheritdoc}
     */
    public function receive($callback)
    {
        $this->declare();

        // if message ack is enabled
        if (!$this->configs['consumer']['no_ack']) {
            $this->channel->basic_qos(null, 1, null);
        }

        $this->channel->basic_consume(
            $this->configs['queue']['name'],
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
    public function getConfigs(): array
    {
        return $this->configs;
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
     */
    private function declare()
    {
        $this->channel = $this->connection->channel();

        $queue_name = '';

        if (!empty($this->configs['exchange']['name'])) {
            // Declare Exchange
            $this->channel->exchange_declare(
                $this->configs['exchange']['name'],
                $this->configs['exchange']['type'],
                $this->configs['exchange']['passive'],
                $this->configs['exchange']['durable'],
                $this->configs['exchange']['auto_delete'],
                $this->configs['exchange']['internal'],
                $this->configs['exchange']['nowait']
            );
        }

        // Declare Queue
        [$queue_name] = $this->channel->queue_declare(
            $this->configs['queue']['name'],
            $this->configs['queue']['passive'],
            $this->configs['queue']['durable'],
            $this->configs['queue']['exclusive'],
            $this->configs['queue']['auto_delete']
        );

        if (empty($queue_name) || empty($this->configs['exchange']['name'])) {
            return;
        }

        foreach ($this->configs['routing']['key'] as $key) {
            $this->channel->queue_bind(
                $queue_name,
                $this->configs['exchange']['name'],
                $key,
                $this->configs['routing']['nowait']
            );
        }
    }
}
