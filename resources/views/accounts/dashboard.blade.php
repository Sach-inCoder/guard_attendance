
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Guard Attendance</h2>
            <p class="text-secondary mb-0">
                Security Workforce Management
            </p>
        </div>
        <h1>Sachin</h1>
        <span class="badge text-bg-success">System Online</span>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">Total Guards</p>
                    <h3 class="fw-bold">900</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">Present Today</p>
                    <h3 class="fw-bold text-success">--</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">Currently On Duty</p>
                    <h3 class="fw-bold text-primary">--</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
