<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = employee::paginate(10);
        return view('employee.index', compact('employee'));
    }

    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Add your validation rules here
        ]);

        employee::create($request->all());

        return redirect()->route('employee.index')->with('success', 'employee created successfully.');
    }

    public function show(employee $employee)
    {
        return view('employee.show', compact('employee'));
    }

    public function edit(employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, employee $employee)
    {
        $request->validate([
            // Add your validation rules here
        ]);

        $employee->update($request->all());

        return redirect()->route('employee.index')->with('success', 'employee updated successfully.');
    }

    public function destroy(employee $employee)
    {
        $employee->delete();

        return redirect()->route('employee.index')->with('success', 'employee deleted successfully.');
    }
}