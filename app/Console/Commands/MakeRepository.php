<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new Repository class in App\Http\Repositories';

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryPath = app_path('Http/Repositories');

        // Buat folder jika belum ada
        if (!File::exists($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        $fileName = "{$name}.php";
        $filePath = "{$repositoryPath}/{$fileName}";

        // Cek jika file sudah ada
        if (File::exists($filePath)) {
            $this->error("Repository {$fileName} already exists!");
            return 1;
        }

        // Template isi file repository
        $content = <<<PHP
<?php

namespace App\Http\Repositories;

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

        $this->info("Repository {$fileName} created successfully.");
        return 0;
    }
}
