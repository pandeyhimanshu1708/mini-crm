@extends('adminlte::page')

@section('title', 'Company Details')

@section('content_header')
    <h1>Company Details: {{ $company->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>ID:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->id }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Name:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->name }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->email ?? 'N/A' }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Logo:</strong>
                </div>
                <div class="col-md-8">
                    @if ($company->logo)
                        <img src="{{ asset('storage/logos/' . $company->logo) }}" alt="{{ $company->name }} Logo" width="150" class="img-thumbnail">
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Website:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->website ?? 'N/A' }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Created At:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->created_at->format('M d, Y H:i A') }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <strong>Last Updated:</strong>
                </div>
                <div class="col-md-8">
                    {{ $company->updated_at->format('M d, Y H:i A') }}
                </div>
            </div>
            <hr>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to Companies</a>
            <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning">Edit Company</a>
        </div>
    </div>
@stop
