<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQSubscriberService
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

    public function subscriber(string $exchange, string $queue, callable $callback): void
    {
        try {
            $this->channel->exchange_declare($exchange, 'fanout', false, true, false);
            $this->channel->queue_declare($queue, false, true, false, false);
            $this->channel->queue_bind($queue, $exchange);
            $this->channel->basic_qos(null, 10, null); // prefetch 10

            Log::info("Subscribed to exchange '{$exchange}' with queue '{$queue}'");

            $this->channel->basic_consume($queue, '', false, false, false, false, function ($msg) use ($exchange, $queue, $callback) {
                if ($msg) {
                    try {
                        $messageContent = $msg->body;
                        Log::info("Received message from queue '{$queue}': " . $messageContent);

                        $callback($exchange, $queue, $messageContent);

                        $this->channel->ack($msg);
                    } catch (\Throwable $e) {
                        Log::error("Error consume message from queue '{$queue}': " . $e->getMessage());
                        // Optional: dead-lettering or requeueing
                    }
                }
            });

            // Start consuming
            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        } catch (\Throwable $e) {
            Log::error("Error subscribing to queue '{$queue}' on exchange '{$exchange}': " . $e->getMessage());
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
