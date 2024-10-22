@extends('layouts.app')
@section('content')
<div class="container">
<h1>Edit {{ ucfirst('product') }}</h1>
<form action="{{ route('product.update', $item->id) }}" method="POST">
@csrf
@method('PUT')
<div class="form-group">
<label for="name">{{ ucfirst('name') }}</label>
<input type="text" class="form-control" name="name" id="name" value="{{ $item->name }}" required>
</div>
<div class="form-group">
<label for="details">{{ ucfirst('details') }}</label>
<input type="text" class="form-control" name="details" id="details" value="{{ $item->details }}" required>
</div>
<button type="submit" class="btn btn-success">Update</button>
<a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div>
@endsection
