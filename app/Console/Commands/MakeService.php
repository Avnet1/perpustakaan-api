<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new Service class in App\Http\Service';

    public function handle()
    {
        $name = $this->argument('name');
        $servicePath = app_path('Http/Services');

        // Buat folder jika belum ada
        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true);
        }

        $fileName = "{$name}.php";
        $filePath = "{$servicePath}/{$fileName}";

        // Cek jika file sudah ada
        if (File::exists($filePath)) {
            $this->error("Service {$fileName} already exists!");
            return 1;
        }

        // Template isi file service
        $content = <<<PHP
<?php

namespace App\Http\Services;

class {$name}
{
    public function __construct()
    {
        //
    }
}
PHP;

        // Simpan file
        File::put($filePath, $content);

        $this->info("Service {$fileName} created successfully.");
        return 0;
    }
}
