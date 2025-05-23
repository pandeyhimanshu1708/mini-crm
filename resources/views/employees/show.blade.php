@extends('adminlte::page')

@section('title', 'Employee Details')

@section('content_header')
    <h1>Employee Details: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
           
            <div class="row">
                <div class="col-md-4">
                    <strong>ID:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->id }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>First Name:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->first_name }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Last Name:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->last_name }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Company:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->company->name ?? 'N/A' }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->email ?? 'N/A' }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Phone:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->phone ?? 'N/A' }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Created At:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->created_at->format('M d, Y H:i A') }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Last Updated:</strong>
                </div>
                <div class="col-md-8">
                    {{ $employee->updated_at->format('M d, Y H:i A') }}
                </div>
            </div>
            <hr>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Employees</a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">Edit Employee</a>

            {{-- Notes Section --}}
            <h4 class="mt-4">Notes</h4>
            {{-- Success/Error messages for notes --}}
            @if (session('success') && str_contains(session('success'), 'Note'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($errors->hasAny(['body', 'noteable_id', 'noteable_type']))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Add New Note</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('notes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="noteable_id" value="{{ $employee->id }}">
                        <input type="hidden" name="noteable_type" value="App\Models\Employee">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="3" placeholder="Enter note content..." required>{{ old('body') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Add Note</button>
                    </form>
                </div>
            </div>

            <div class="notes-list mt-3">
                @forelse ($employee->notes->sortByDesc('created_at') as $note)
                    <div class="card card-secondary card-outline mb-2">
                        <div class="card-body p-2">
                            <small class="text-muted float-right">{{ $note->created_at->format('M d, Y H:i A') }} by {{ $note->user->name ?? 'N/A' }}</small>
                            <p class="mb-0">{{ $note->body }}</p>
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="float-right ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this note?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No notes available for this employee.</p>
                @endforelse
            </div>

           
        </div>
    </div>
@stop
