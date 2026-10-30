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
                    <div class="card ">
                        <div class="card-body">
                            <p class="text-secondary mb-2">Active Guards</p>
                            <h3 class="fw-bold">{{$stats['guards']}}</h3>
                            <a href="{{ route('admin.guards.index') }}" class="btn btn-primary btn-sm">View <i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-body">
                            <p class="text-secondary mb-2">Active Companies</p>
                            <h3 class="fw-bold">{{$stats['companies']}}</h3>
                            <a href="{{ route('admin.companies.index') }}" class="btn btn-primary btn-sm">View <i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
                
        
                <div class="col-md-4" hidden>
                    <div class="card ">
                        <div class="card-body">
                            <p class="text-secondary mb-2">Currently On Duty</p>
                            <h3 class="fw-bold text-primary">--</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
