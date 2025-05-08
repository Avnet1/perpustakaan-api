<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisherService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.pass'),
            config('rabbitmq.vhost')
        );

        $this->channel = $this->connection->channel();
    }

    public function publisher(array $data, string $exchange, string $queue, string $routingKey = ''): void
    {
        try {
            // Declare exchange
            $this->channel->exchange_declare($exchange, 'fanout', false, true, false);

            // Declare queue
            $this->channel->queue_declare($queue, false, true, false, false);

            // Bind queue to exchange with routing key
            $this->channel->queue_bind($queue, $exchange, $routingKey);

            // Create message
            $message = new AMQPMessage(json_encode($data), [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]);

            // routing key kosong karena fanout
            $this->channel->basic_publish($message, $exchange, $routingKey);

            Log::info("Success published to exchange '{$exchange}' and queue '{$queue}'");
        } catch (\Throwable $e) {
            Log::error("Error publishing to exchange '{$exchange}' and queue '{$queue}': " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
