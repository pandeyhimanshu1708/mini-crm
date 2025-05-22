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
        </div>
    </div>
@stop
