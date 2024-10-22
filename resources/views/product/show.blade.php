@extends('layouts.app')
@section('content')
<div class="container">
<h1>{{ ucfirst('product') }} Details</h1>
<table class="table table-bordered">
<tr>
<th>{{ ucfirst('id') }}</th>
<td>{{ $product->id }}</td>
</tr>
<tr>
<th>{{ ucfirst('name') }}</th>
<td>{{ $product->name }}</td>
</tr>
<tr>
<th>{{ ucfirst('details') }}</th>
<td>{{ $product->details }}</td>
</tr>
<tr>
<th>{{ ucfirst('created_at') }}</th>
<td>{{ $product->created_at }}</td>
</tr>
<tr>
<th>{{ ucfirst('updated_at') }}</th>
<td>{{ $product->updated_at }}</td>
</tr>
</table>
<a href="{{ route('product.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
