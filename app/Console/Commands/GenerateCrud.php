<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate CRUD operations with views and controller based on table columns';

    public function handle()
    {
        $name = $this->argument('name');
        $table = strtolower($name);
        $capitalname = ucfirst(Str::camel($name));

        // Fetch the table columns dynamically
        $columns = $this->getTableColumns($table);

        if (empty($columns)) {
            $this->error("Table '{$table}' not found or has no columns.");
            return;
        }

        // Generate the views and controller
        $this->generateViews($name, $columns);
        $this->generateModel($name);
        $this->generateController($name);
        
        // Provide the user with the resource route declaration
        $routeDeclaration = "Route::resource('{$table}', {$capitalname}Controller::class);";
        $this->info("CRUD for table '{$table}' generated successfully.");
        $this->line("Add the following line to your routes/web.php:");
        $this->line($routeDeclaration);
    }

    protected function getTableColumns($table)
    {
        if (Schema::hasTable($table)) {
            return Schema::getColumnListing($table);
        } else {
            return [];
        }
    }

    protected function generateViews($name, $columns)
    {
        $viewPath = resource_path("views/{$name}");
        File::ensureDirectoryExists($viewPath);

        // Generate all the views: index, create, edit, show, etc.
        File::put("{$viewPath}/index.blade.php", $this->generateIndexView($name, $columns));
        File::put("{$viewPath}/create.blade.php", $this->generateCreateView($name, $columns));
        File::put("{$viewPath}/edit.blade.php", $this->generateEditView($name, $columns));
        File::put("{$viewPath}/show.blade.php", $this->generateShowView($name, $columns));
    }

    protected function generateModel($name)
    {
        // Capitalize the first letter of the table name for the model class name
        $capitalizedName = ucfirst($name);
        $modelPath = app_path("Models/{$capitalizedName}.php");

        // Create the content of the model file
        $modelContent = <<<EOD
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class {$capitalizedName} extends Model
        {
            protected \$table = '$name'; // Define the table name
            protected \$guarded = []; // Guarded properties for mass assignment
        }
        EOD;

        // Write the model content to the appropriate file
        File::put($modelPath, $modelContent);

        // Print success message
        $this->info("Model {$capitalizedName}.php created successfully.");
    }

    protected function generateController($name)
    {
        $capitalname = ucfirst(Str::camel($name));
        $controllerPath = app_path("Http/Controllers/{$capitalname}Controller.php");

        $controllerContent = <<<EOD
        <?php

        namespace App\Http\Controllers;

        use App\Models\\$capitalname;
        use Illuminate\Http\Request;

        class {$capitalname}Controller extends Controller
        {
            public function index()
            {
                \$$name = $name::paginate(10);
                return view('$name.index', compact('$name'));
            }

            public function create()
            {
                return view('$name.create');
            }

            public function store(Request \$request)
            {
                \$request->validate([
                    // Add your validation rules here
                ]);

                $name::create(\$request->all());

                return redirect()->route('$name.index')->with('success', '$name created successfully.');
            }

            public function show($name \$$name)
            {
                return view('$name.show', compact('$name'));
            }

            public function edit($name \$$name)
            {
                return view('$name.edit', compact('$name'));
            }

            public function update(Request \$request, $name \$$name)
            {
                \$request->validate([
                    // Add your validation rules here
                ]);

                \${$name}->update(\$request->all());

                return redirect()->route('$name.index')->with('success', '$name updated successfully.');
            }

            public function destroy($name \$$name)
            {
                \${$name}->delete();

                return redirect()->route('$name.index')->with('success', '$name deleted successfully.');
            }
        }
        EOD;

        File::put($controllerPath, $controllerContent);
        $this->info("Controller {$name}Controller.php created successfully.");
    }


    // Generate index view (list with pagination)
    protected function generateIndexView($name, $columns)
    {
        $variableName = strtolower($name); // e.g., 'product'

        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>{{ ucfirst('$name') }} List</h1>\n<a href=\"{{ route('$variableName.create') }}\" class=\"btn btn-primary mb-3\">Add {{ ucfirst('$name') }}</a>\n";
        $html .= "<table class=\"table table-bordered\">\n<thead>\n<tr>\n";

        $html .= "<th>No.</th>\n"; 

        foreach ($columns as $column) {
            if ($column != 'id' && $column != 'created_at' && $column != 'updated_at') {
                $html .= "<th>{{ ucfirst('$column') }}</th>\n"; 
            }
        }

        $html .= "<th>Actions</th>\n</tr>\n</thead>\n<tbody>\n";

        $html .= "@foreach(\$$variableName as \$item)\n<tr>\n"; 

        $html .= "<td>{{ \$loop->iteration + (\${$variableName}->perPage() * (\${$variableName}->currentPage() - 1)) }}</td>\n"; 

        // Loop through the columns again, displaying the data for each item
        foreach ($columns as $column) {
            if ($column != 'id' && $column != 'created_at' && $column != 'updated_at') {
                $html .= "<td>{{ \$item->$column }}</td>\n"; 
            }
        }

        // Actions (Edit and Delete buttons)
        $html .= "<td>\n<a href=\"{{ route('$variableName.edit', \$item->id) }}\" class=\"btn btn-warning\">Edit</a>\n"; 
        $html .= "<form action=\"{{ route('$variableName.destroy', \$item->id) }}\" method=\"POST\" style=\"display:inline;\">\n";
        $html .= "@csrf\n@method('DELETE')\n<button type=\"submit\" class=\"btn btn-danger\">Delete</button>\n</form>\n</td>\n</tr>\n@endforeach\n";

        $html .= "</tbody>\n</table>\n";

        // Correctly reference the pagination
        $html .= "<div class=\"d-flex justify-content-center\">{{ \${$variableName}->links() }}</div>\n"; 
        $html .= "</div>\n@endsection\n";

        return $html;
    }

    // Generate create view (form for adding new entry)
    protected function generateCreateView($name, $columns)
    {
        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>Add New {{ ucfirst('$name') }}</h1>\n";
        $html .= "<form action=\"{{ route('$name.store') }}\" method=\"POST\">\n@csrf\n";

        foreach ($columns as $column) {
            if ($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at') {
                $html .= "<div class=\"form-group\">\n";
                $html .= "<label for=\"$column\">{{ ucfirst('$column') }}</label>\n";
                $html .= "<input type=\"text\" name=\"$column\" class=\"form-control\" id=\"$column\">\n";
                $html .= "</div>\n";
            }
        }

        $html .= "<button type=\"submit\" class=\"btn btn-success\">Save</button>\n</form>\n</div>\n@endsection\n";

        return $html;
    }

    protected function generateEditView($name, $columns)
    {
        $variableName = strtolower($name); // Convert to lowercase for variable name
        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>Edit {{ ucfirst('$name') }}</h1>\n";
    
        // Correctly reference the variable name for the form action
        $html .= "<form action=\"{{ route('$variableName.update', \${$variableName}->id) }}\" method=\"POST\">\n"; 
        $html .= "@csrf\n@method('PUT')\n";
    
        // Loop through columns to create form fields
        foreach ($columns as $column) {
            if ($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at') {
                $html .= "<div class=\"form-group\">\n";
                $html .= "<label for=\"$column\">{{ ucfirst('$column') }}</label>\n";
                // Use the dynamic variable for the value
                $html .= "<input type=\"text\" class=\"form-control\" name=\"$column\" id=\"$column\" value=\"{{ \${$variableName}->$column }}\" required>\n";
                $html .= "</div>\n";
            }
        }
    
        $html .= "<button type=\"submit\" class=\"btn btn-success\">Update</button>\n";
        $html .= "<a href=\"{{ route('$variableName.index') }}\" class=\"btn btn-secondary\">Cancel</a>\n";
        $html .= "</form>\n</div>\n@endsection\n";
    
        return $html;
    }

    // Generate show view (view for displaying a single entry)
    protected function generateShowView($name, $columns)
    {
        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>{{ ucfirst('$name') }} Details</h1>\n<table class=\"table table-bordered\">\n";

        foreach ($columns as $column) {
            $html .= "<tr>\n<th>{{ ucfirst('$column') }}</th>\n<td>{{ \$$name->$column }}</td>\n</tr>\n";
        }

        $html .= "</table>\n<a href=\"{{ route('$name.index') }}\" class=\"btn btn-secondary\">Back to List</a>\n</div>\n@endsection\n";

        return $html;
    }
}