@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        {{-- Widget: Total Companies --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalCompanies }}</h3>
                    <p>Total Companies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('companies.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        {{-- Widget: Total Employees --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalEmployees }}</h3>
                    <p>Total Employees</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('employees.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
    </div>

    <div class="row">
       
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Companies by Email Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="companyEmailChart" style="height:250px"></canvas>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Recent Activities</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse ($recentCompanies as $company)
                            <li class="item">
                                <div class="product-img">
                                    @if ($company->logo)
                                        <img src="{{ asset('storage/logos/' . $company->logo) }}" alt="Company Logo" class="img-size-32">
                                    @else
                                        <i class="fas fa-building img-size-32 text-muted"></i>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <a href="{{ route('companies.show', $company->id) }}" class="product-title">{{ $company->name }}
                                        <span class="badge badge-info float-right">New Company</span></a>
                                    <span class="product-description">
                                        Added on {{ $company->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            
                        @endforelse

                        @forelse ($recentEmployees as $employee)
                            <li class="item">
                                <div class="product-img">
                                    <i class="fas fa-user-tie img-size-32 text-primary"></i>
                                </div>
                                <div class="product-info">
                                    <a href="{{ route('employees.show', $employee->id) }}" class="product-title">{{ $employee->first_name }} {{ $employee->last_name }}
                                        <span class="badge badge-success float-right">New Employee</span></a>
                                    <span class="product-description">
                                        Added on {{ $employee->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            {{-- No recent employees --}}
                        @endif
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('companies.index') }}" class="uppercase">View All Companies</a> |
                    <a href="{{ route('employees.index') }}" class="uppercase">View All Employees</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            // Chart.js for Companies by Email Status
            var ctx = document.getElementById('companyEmailChart').getContext('2d');
            var companyEmailChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['With Email', 'Without Email'],
                    datasets: [{
                        data: [{{ $companiesWithEmail }}, {{ $companiesWithoutEmail }}],
                        backgroundColor: ['#007bff', '#dc3545'], // Blue and Red
                        hoverBackgroundColor: ['#0056b3', '#c82333']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                                return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop
