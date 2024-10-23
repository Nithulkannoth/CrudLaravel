@extends('layouts.app')
@section('content')
<div class="container">
<h1>{{ ucfirst('employee') }} List</h1>
<a href="{{ route('employee.create') }}" class="btn btn-primary mb-3">Add New {{ ucfirst('employee') }}</a>
<table class="table table-bordered">
<thead>
<tr>
<th>{{ ucfirst('id') }}</th>
<th>{{ ucfirst('first_name') }}</th>
<th>{{ ucfirst('last_name') }}</th>
<th>{{ ucfirst('job_title') }}</th>
<th>{{ ucfirst('created_at') }}</th>
<th>{{ ucfirst('updated_at') }}</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($employee as $item)
<tr>
<td>{{ $item->id }}</td>
<td>{{ $item->first_name }}</td>
<td>{{ $item->last_name }}</td>
<td>{{ $item->job_title }}</td>
<td>{{ $item->created_at }}</td>
<td>{{ $item->updated_at }}</td>
<td>
<a href="{{ route('employee.edit', $item->id) }}" class="btn btn-warning">Edit</a>
<form action="{{ route('employee.destroy', $item->id) }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-danger">Delete</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
<div class="d-flex justify-content-center">{{ $employee->links() }}</div>
</div>
@endsection
