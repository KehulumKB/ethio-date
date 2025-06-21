<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeHelper extends Command
{
    protected $signature = 'make:helper {name : The name of the helper file}';
    protected $description = 'Create a new global helper file in app/Helpers';

    public function handle(): void
    {
        $name = Str::snake($this->argument('name'));
        $fileName = "{$name}.php";
        $filePath = app_path("Helpers/{$fileName}");

        if (File::exists($filePath)) {
            $this->error("❌ Helper file already exists: {$fileName}");
            return;
        }

        File::ensureDirectoryExists(app_path('Helpers'));

        $stub = <<<PHP
<?php

if (!function_exists('example_helper')) {
    function example_helper()
    {
        return 'This is a helper!';
    }
}
PHP;

        File::put($filePath, $stub);
        $this->info("✅ Helper file created: app/Helpers/{$fileName}");

        $this->warn("📌 Don't forget to add it to composer.json autoload > files and run: composer dump-autoload");
    }
}
