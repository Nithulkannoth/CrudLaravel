@extends('layouts.app')
@section('content')
<div class="container">
<h1>Add New {{ ucfirst('employee') }}</h1>
<form action="{{ route('employee.store') }}" method="POST">
@csrf
<div class="form-group">
<label for="first_name">{{ ucfirst('first_name') }}</label>
<input type="text" name="first_name" class="form-control" id="first_name">
</div>
<div class="form-group">
<label for="last_name">{{ ucfirst('last_name') }}</label>
<input type="text" name="last_name" class="form-control" id="last_name">
</div>
<div class="form-group">
<label for="job_title">{{ ucfirst('job_title') }}</label>
<input type="text" name="job_title" class="form-control" id="job_title">
</div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection
