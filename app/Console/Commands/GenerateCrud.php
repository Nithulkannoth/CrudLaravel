<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate CRUD operations with views and controller based on table columns';

    public function handle()
    {
        $name = $this->argument('name');
        $table = strtolower($name);

        // Fetch the table columns dynamically
        $columns = $this->getTableColumns($table);

        if (empty($columns)) {
            $this->error("Table '{$table}' not found or has no columns.");
            return;
        }

        // Generate the views and other necessary files
        $this->generateViews($name, $columns);
        $this->generateController($name);
        $this->info("CRUD for table '{$table}' generated successfully.");
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

    protected function generateController($name)
    {
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");

        $controllerContent = <<<EOD
<?php

namespace App\Http\Controllers;

use App\Models\\$name;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
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

    public function show($name \$name)
    {
        return view('$name.show', compact('$name'));
    }

    public function edit($name \$name)
    {
        return view('$name.edit', compact('$name'));
    }

    public function update(Request \$request, $name \$name)
    {
        \$request->validate([
            // Add your validation rules here
        ]);

        \$name->update(\$request->all());

        return redirect()->route('$name.index')->with('success', '$name updated successfully.');
    }

    public function destroy($name \$name)
    {
        \$name->delete();

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
        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>{{ ucfirst('$name') }} List</h1>\n<a href=\"{{ route('$name.create') }}\" class=\"btn btn-primary mb-3\">Add New {{ ucfirst('$name') }}</a>\n";
        $html .= "<table class=\"table table-bordered\">\n<thead>\n<tr>\n";

        foreach ($columns as $column) {
            $html .= "<th>{{ ucfirst('$column') }}</th>\n";
        }

        $html .= "<th>Actions</th>\n</tr>\n</thead>\n<tbody>\n";
        $html .= "@foreach(\$$name as \$$name)\n<tr>\n";

        foreach ($columns as $column) {
            $html .= "<td>{{ \$$name->$column }}</td>\n";
        }

        $html .= "<td>\n<a href=\"{{ route('$name.edit', \$$name->id) }}\" class=\"btn btn-warning\">Edit</a>\n";
        $html .= "<form action=\"{{ route('$name.destroy', \$$name->id) }}\" method=\"POST\" style=\"display:inline;\">\n";
        $html .= "@csrf\n@method('DELETE')\n<button type=\"submit\" class=\"btn btn-danger\">Delete</button>\n</form>\n</td>\n</tr>\n@endforeach\n</tbody>\n</table>\n";
        $html .= "<div class=\"d-flex justify-content-center\">{{ \$$name->links() }}</div>\n</div>\n@endsection\n";

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

    // Generate edit view (form for editing an existing entry)
    protected function generateEditView($name, $columns)
    {
        $html = "@extends('layouts.app')\n@section('content')\n<div class=\"container\">\n";
        $html .= "<h1>Edit {{ ucfirst('$name') }}</h1>\n";
        $html .= "<form action=\"{{ route('$name.update', \$$name->id) }}\" method=\"POST\">\n@csrf\n@method('PUT')\n";

        foreach ($columns as $column) {
            if ($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at') {
                $html .= "<div class=\"form-group\">\n";
                $html .= "<label for=\"$column\">{{ ucfirst('$column') }}</label>\n";
                $html .= "<input type=\"text\" name=\"$column\" class=\"form-control\" id=\"$column\" value=\"{{ \$$name->$column }}\">\n";
                $html .= "</div>\n";
            }
        }

        $html .= "<button type=\"submit\" class=\"btn btn-success\">Update</button>\n</form>\n</div>\n@endsection\n";

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