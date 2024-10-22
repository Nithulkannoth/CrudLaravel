@extends('layouts.app')
@section('content')
<div class="container">
<h1>Add New {{ ucfirst('product') }}</h1>
<form action="{{ route('product.store') }}" method="POST">
@csrf
<div class="form-group">
<label for="name">{{ ucfirst('name') }}</label>
<input type="text" name="name" class="form-control" id="name">
</div>
<div class="form-group">
<label for="details">{{ ucfirst('details') }}</label>
<input type="text" name="details" class="form-control" id="details">
</div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection
