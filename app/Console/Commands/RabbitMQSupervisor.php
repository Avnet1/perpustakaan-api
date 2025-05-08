<?php

namespace App\Console\Commands;

use App\Models\RabbitMQWorker;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RabbitMQSupervisor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:supervisor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Run All RabbitMQ Workers. System Get Queue And Exchange From database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workers = RabbitMQWorker::all();

        if ($workers->isEmpty()) {
            $this->warn('No RabbitMQ (Queue and Exchange) workers found in database.');
            return;
        }

        foreach ($workers as $worker) {
            $exchange = $worker->exchange;
            $queue = $worker->queue;

            $process = new Process(['php', 'artisan', 'rabbitmq:subscribe', $exchange, $queue]);
            $process->start();

            $this->info("Started subscriber for [{$exchange}] -> [{$queue}]");
        }

        $this->info("All subscribers started. Running as master...");

        // Optional: keep the master process alive
        while (true) {
            sleep(60);
        }
    }
}
