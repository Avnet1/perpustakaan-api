<?php

namespace App\Console\Commands;

use App\Services\RabbitMQSubscriberService;
use Illuminate\Console\Command;

class RabbitMQSubscriberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume {exchange} {queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe A Specific RabbitMQ With List Exchange And Queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $exchange = $this->argument('exchange');
        $queue = $this->argument('queue');

        $subscriber = new RabbitMQSubscriberService();
        $subscriber->subscriber($exchange, $queue, function ($exchange, $queue, $messageContent) {
            // Logika pemrosesan message
            logger("Processing message from {$queue}: {$messageContent}");

            // ... Tambahkan logika bisnis sesuai kebutuhan
        });
    }
}
