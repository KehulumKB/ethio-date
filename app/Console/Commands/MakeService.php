<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:service {name : The name of the service class}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a new service class in app/Services';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $className = ucfirst($name);
        $path = app_path("Services/{$className}.php");

        if (File::exists($path)) {
            $this->error("Service {$className} already exists!");
            return;
        }

        File::ensureDirectoryExists(app_path('Services'));

        $stub = <<<PHP
<?php

namespace App\Services;

class {$className}
{
    public function handle()
    {
        // TODO: Implement service logic
    }
}
PHP;

        File::put($path, $stub);

        $this->info("Service class created: App\\Services\\{$className}");
    }
}
