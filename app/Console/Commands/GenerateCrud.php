<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Create a full CRUD for a model, including pagination, views, and migration';
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $modelName = ucfirst($name);
        
        // Generate model with migration
        Artisan::call('make:model', ['name' => $modelName, '--migration' => true]);
        $this->info("Model $modelName and migration created successfully!");

        // Generate resourceful controller
        Artisan::call('make:controller', ['name' => "{$modelName}Controller", '--resource' => true]);
        $this->info("Controller {$modelName}Controller created successfully!");

        // Generate views
        $this->generateViews($modelName);

        // Append routes
        $this->appendRoutes($modelName);

        // Generate migration columns
        $this->modifyMigration($name);

        $this->info("CRUD operations for $modelName generated successfully, including pagination of 10 items per page.");
    }

    protected function generateViews($name)
    {
        $viewPath = resource_path("views/$name");
        if (! $this->filesystem->isDirectory($viewPath)) {
            $this->filesystem->makeDirectory($viewPath, 0755, true);
        }

        $views = ['index', 'create', 'edit', 'show'];
        foreach ($views as $view) {
            $this->filesystem->put("$viewPath/$view.blade.php", $this->getStubContent($view));
        }

        $this->info("Views for $name generated successfully!");
    }

    protected function getStubContent($view)
    {
        return $this->filesystem->get(base_path("stubs/views/$view.stub"));
    }

    protected function appendRoutes($name)
    {
        $route = strtolower($name);
        $content = "\nRoute::resource('$route', '{$name}Controller');";
        $this->filesystem->append(base_path('routes/web.php'), $content);
        $this->info("Routes for $name appended successfully!");
    }

    protected function modifyMigration($name)
    {
        $migrationPath = database_path('migrations');
        $migrationFiles = scandir($migrationPath);
        
        foreach ($migrationFiles as $file) {
            if (strpos($file, "create_{$name}_table") !== false) {
                $filePath = $migrationPath . '/' . $file;
                $migrationContent = file_get_contents($filePath);
                
                // Replace the schema columns with desired fields (example fields)
                $migrationContent = str_replace(
                    "\$table->id();",
                    "\$table->id();\n\t\t\t\$table->string('name');\n\t\t\t\$table->text('description');\n\t\t\t\$table->integer('quantity');\n\t\t\t\$table->decimal('price', 8, 2);",
                    $migrationContent
                );
                
                file_put_contents($filePath, $migrationContent);
                $this->info("Migration for $name modified with necessary fields.");
                break;
            }
        }
    }
}