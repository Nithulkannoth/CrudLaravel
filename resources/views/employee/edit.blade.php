@extends('layouts.app')
@section('content')
<div class="container">
<h1>Edit {{ ucfirst('employee') }}</h1>
<form action="{{ route('employee.update', $employee->id) }}" method="POST">
@csrf
@method('PUT')
<div class="form-group">
<label for="first_name">{{ ucfirst('first_name') }}</label>
<input type="text" class="form-control" name="first_name" id="first_name" value="{{ $employee->first_name }}" required>
</div>
<div class="form-group">
<label for="last_name">{{ ucfirst('last_name') }}</label>
<input type="text" class="form-control" name="last_name" id="last_name" value="{{ $employee->last_name }}" required>
</div>
<div class="form-group">
<label for="job_title">{{ ucfirst('job_title') }}</label>
<input type="text" class="form-control" name="job_title" id="job_title" value="{{ $employee->job_title }}" required>
</div>
<button type="submit" class="btn btn-success">Update</button>
<a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div>
@endsection
