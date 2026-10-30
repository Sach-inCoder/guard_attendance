@extends('layouts.app')

@section('title', 'Dashboard')
@push('style')
    <style>

    </style>
@endpush
@php
    $guard = auth()->user()->guardProfile;

    $assignment = $guard?->assignments()
        ->with(['site', 'assignedBy'])
        ->where('status', 'active')
        ->whereDate('starts_on', '<=', today())
        ->where(function ($query) {
            $query->whereNull('ends_on')
                ->orWhereDate('ends_on', '>=', today());
        })
        ->latest('id')
        ->first();
@endphp
@section('content')
    <div class="card border-dark">
        <div class="card-header">
            <h6 class="card-title">Dashboard</h6>
        </div>
        <div class="card-body">
            <p class="card-text">
                MY ID : <b>{{ auth()->user()->guardProfile?->employee_id ?? 'N/A' }}</b>
            </p>
            <p class="card-text">
                Joining : <b>{{ auth()->user()->guardProfile?->joining_date ?? 'N/A' }}</b>
            </p>
            <p class="card-text">
                Duty On:
                <b>{{ $assignment?->site?->name ?? 'Not Assigned' }}</b>
            </p>
            <p class="card-text">
                Address:
                <b>{{ $assignment?->site->address }}</b>
            </p>

            <p class="card-text">
                Site Assigned By:
                <b>{{ $assignment?->assignedBy?->name ?? 'N/A' }}</b>
            </p>
        </div>
    </div>
    <div class="text-center py-5">
        <button class="btn btn-lg btn-outline-success">
            Check In
        </button>
    </div>
@endsection