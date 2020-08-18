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
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ],
        'queue_name' => 'default',
        'vhost' => '/',
        'routing_key' => 'default',
        // The default exchange is implicitly bound to every queue, with a routing key equal to the queue name
        'exchange' => '',
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
        $this->server = $server;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;

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

        $this->configs['queue_name'] = (isset($configs['queue_name']))
            ? $configs['queue_name'] : $this->configs['queue_name'];

        $this->configs['vhost'] = (isset($configs['vhost']))
            ? $configs['vhost'] : $this->configs['vhost'];

        $this->configs['routing_key'] = (isset($configs['routing_key']))
            ? $configs['routing_key'] : $this->configs['routing_key'];

        $this->configs['exchange'] = (isset($configs['exchange']))
            ? $configs['exchange'] : $this->configs['exchange'];
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
        $this->declareQueue();

        $msg = new AMQPMessage(
            (string) $message,
            $this->configs['delivery']
        );

        $this->channel->basic_publish($msg, $this->configs['exchange'], $this->configs['routing_key']);
    }

    /**
     * {@inheritdoc}
     */
    public function receive($callback)
    {
        $this->declareQueue();

        // if message ack is enabled
        if (!$this->configs['consumer']['no_ack']) {
            $this->channel->basic_qos(null, 1, null);
        }

        $this->channel->basic_consume(
            $this->configs['queue_name'],
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
     *
     * @return void
     */
    private function declareQueue()
    {
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(
            $this->configs['queue_name'],
            $this->configs['queue']['passive'],
            $this->configs['queue']['durable'],
            $this->configs['queue']['exclusive'],
            $this->configs['queue']['auto_delete']
        );
    }
}
