<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function index()
    {
        $product = product::paginate(10);
        return view('product.index', compact('product'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Add your validation rules here
        ]);

        product::create($request->all());

        return redirect()->route('product.index')->with('success', 'product created successfully.');
    }

    public function show(product $product)
    {
        return view('product.show', compact('product'));
    }

    public function edit(product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, product $name)
    {
        $request->validate([
            // Add your validation rules here
        ]);

        dd($name);

        $name->update($request->all());

        return redirect()->route('product.index')->with('success', 'product updated successfully.');
    }

    public function destroy(product $name)
    {
        $name->delete();

        return redirect()->route('product.index')->with('success', 'product deleted successfully.');
    }
}