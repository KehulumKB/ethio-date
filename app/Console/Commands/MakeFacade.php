<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFacade extends Command
{
    protected $signature = 'make:facade {name : The name of the facade class} {--service= : The service class to bind (default: name + Service)}';
    protected $description = 'Create a new facade class in app/Facades';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $serviceClass = $this->option('service') ?? "{$name}Service";
        $serviceKey = Str::camel($name);

        $facadePath = app_path("Facades/{$name}.php");

        if (File::exists($facadePath)) {
            $this->error("Facade {$name} already exists!");
            return;
        }

        File::ensureDirectoryExists(app_path('Facades'));

        $stub = <<<PHP
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class {$name} extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '{$serviceKey}';
    }
}
PHP;

        File::put($facadePath, $stub);
        $this->info("✅ Facade created: App\\Facades\\{$name}");

        // Suggest binding the service if not already done
        $this->warn("👉 Don't forget to bind '{$serviceKey}' to your {$serviceClass} in a service provider.");
    }
}
