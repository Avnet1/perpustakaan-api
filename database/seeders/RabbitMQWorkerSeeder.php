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
            ['exchange' => 'user_exchange', 'queue' => 'user_queue'],
            ['exchange' => 'order_exchange', 'queue' => 'order_queue'],
            ['exchange' => 'notif_exchange', 'queue' => 'notif_queue'],
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
