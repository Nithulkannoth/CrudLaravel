@extends('layouts.app')
@section('content')
<div class="container">
<h1>{{ ucfirst('product') }} List</h1>
<a href="{{ route('product.create') }}" class="btn btn-primary mb-3">Add {{ ucfirst('product') }}</a>
<table class="table table-bordered">
<thead>
<tr>
<th>No.</th>
<th>{{ ucfirst('name') }}</th>
<th>{{ ucfirst('details') }}</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($product as $item)
<tr>
<td>{{ $loop->iteration + ($product->perPage() * ($product->currentPage() - 1)) }}</td>
<td>{{ $item->name }}</td>
<td>{{ $item->details }}</td>
<td>
<a href="{{ route('product.edit', $item->id) }}" class="btn btn-warning">Edit</a>
<form action="{{ route('product.destroy', $item->id) }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-danger">Delete</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
<div class="d-flex justify-content-center">{{ $product->links() }}</div>
</div>
@endsection
