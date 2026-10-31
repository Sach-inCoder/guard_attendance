@extends('layouts.admin_app')
@section('title', 'Dashboard')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h2 class="fw-bold mb-1">Guard Attendance</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-secondary mb-0">
                        Security Workforce Management
                    </p>
                </div>
                <a href="{{ route('admin.assignments.create') }}" class="badge text-bg-success">Assign Guard</a>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-secondary mb-2">Active Guards</p>
                            <h3 class="fw-bold">{{$stats['guards']}}</h3>
                        </div>
                        <a href="{{ route('admin.guards.index') }}" class="card-footer text-white btn bg-primary btn-sm mt-auto d-block">View <i class="fas fa-eye"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-secondary mb-2">Active Companies</p>
                            <h3 class="fw-bold">{{$stats['companies']}}</h3>
                        </div>
                        <a href="{{ route('admin.companies.index') }}" class="card-footer text-white btn bg-primary btn-sm mt-auto d-block">View <i class="fas fa-eye"></i></a>
                    </div>
                </div>
                
        
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            {{-- <p class="text-secondary mb-2">Attendance Reports</p> --}}
                            <h3 class="fw-bold">Attendance Reports</h3>
                        </div>
                        <a href="{{ route('admin.reports.attendance-register') }}" class="card-footer text-white btn bg-primary btn-sm mt-auto d-block">Report <i class="fa-solid fa-clipboard-user"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
