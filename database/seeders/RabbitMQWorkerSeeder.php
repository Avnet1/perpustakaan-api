<?php

namespace Database\Seeders;

use App\Models\RabbitMQWorker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RabbitMQWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['exchange' => config('constants.message_broker.exchange.organization'), 'queue' => config('constants.message_broker.queue.organization')],
            ['exchange' => config('constants.message_broker.exchange.notification'), 'queue' => config('constants.message_broker.queue.notification')],
        ];

        for ($i = 0; $i < count($data); $i++) {
            $element = $data[$i];

            $row = RabbitMQWorker::where(['exchange' => $element['exchange'], 'queue' => $element['queue']])->first();

            if (!$row) {
                RabbitMQWorker::store($element);
            }
        }
    }
}
