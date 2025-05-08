<?php

namespace App\Console\Commands;

use App\Models\RabbitMQWorker;
use Illuminate\Console\Command;

class RabbitMQSubscriberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe To RabbitMQ With List Exchanges And Queues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workers = RabbitMQWorker::all();

        if (!$workers) {
        }
    }
}
