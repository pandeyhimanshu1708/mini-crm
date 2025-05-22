@extends('adminlte::page')

@section('title', 'Companies')

@section('content_header')
    <h1>Companies</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Companies</h3>
            <div class="card-tools">
                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">Add New Company</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table id="companies-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Logo</th>
                        <th>Website</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>
                                @if ($company->logo)
                                    <img src="{{ asset('storage/logos/' . $company->logo) }}" alt="{{ $company->name }} Logo" width="50" height="50" class="img-thumbnail rounded-circle">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $company->website }}</td>
                            <td>
                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-info btn-xs">View</a>
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning btn-xs">Edit</a>
                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this company?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $companies->links('pagination::bootstrap-5') }} {{-- Use Bootstrap 5 pagination styling --}}
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any custom CSS here --}}
@stop

@section('js')
    <script>
        $(function () {
            // Initialize Datatables (optional, as pagination is handled by Laravel)
            // If you want full Datatables functionality (search, sort on client-side),
            // you'd need to fetch all data or use server-side processing.
            // For now, we'll just use basic table styling.
            // $('#companies-table').DataTable({
            //     "paging": false, // Disable DataTables pagination since Laravel handles it
            //     "lengthChange": false,
            //     "searching": true,
            //     "ordering": true,
            //     "info": false,
            //     "autoWidth": false,
            //     "responsive": true,
            // });
        });
    </script>
@stop
