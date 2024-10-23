@extends('layouts.app')
@section('content')
<div class="container">
<h1>{{ ucfirst('employee') }} Details</h1>
<table class="table table-bordered">
<tr>
<th>{{ ucfirst('id') }}</th>
<td>{{ $employee->id }}</td>
</tr>
<tr>
<th>{{ ucfirst('first_name') }}</th>
<td>{{ $employee->first_name }}</td>
</tr>
<tr>
<th>{{ ucfirst('last_name') }}</th>
<td>{{ $employee->last_name }}</td>
</tr>
<tr>
<th>{{ ucfirst('job_title') }}</th>
<td>{{ $employee->job_title }}</td>
</tr>
<tr>
<th>{{ ucfirst('created_at') }}</th>
<td>{{ $employee->created_at }}</td>
</tr>
<tr>
<th>{{ ucfirst('updated_at') }}</th>
<td>{{ $employee->updated_at }}</td>
</tr>
</table>
<a href="{{ route('employee.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
