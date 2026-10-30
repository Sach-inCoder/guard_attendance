@extends('layouts.admin_app')
@section('page-title', 'Guard Assignments')
@section('content')
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Add Guard Assignment</h4>
                        <p class="text-muted mb-0">
                            Create a new Guard Assignment
                        </p>
                    </div>
                    <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.assignments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="guard_id" class="form-label">Select Guard</label>
                        <select name="guard_id" id="guard_id" class="form-select" required>
                            <option value="">Choose guard</option>
                            @foreach ($guards as $guard)
                                <option value="{{ $guard->id }}" @selected(old('guard_id') == $guard->id)>
                                    {{ $guard->employee_id }} -
                                    {{ $guard->user?->name ?? $guard->full_name ?? 'Unnamed Guard' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="site_id" class="form-label">Select Site</label>
                        <select name="site_id" id="site_id" class="form-select" required>
                            {{-- <option value="">Choose site</option> --}}
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" @selected(old('site_id') == $site->id)>
                                    {{ $site->company['name'] }} | {{ $site->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="shift_id" class="form-label">Select Shift</label>
                        <select name="shift_id" id="shift_id" class="form-select" required>
                            <option value="">Choose shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}" @selected(old('shift_id') == $shift->id)>
                                    {{ $shift->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="starts_on" class="form-label">Start Date</label>
                            <input type="date" name="starts_on" id="starts_on" class="form-control"
                                value="{{ old('starts_on', today()->toDateString()) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ends_on" class="form-label">End Date (Optional)</label>
                            <input type="date" name="ends_on" id="ends_on" class="form-control"
                                value="{{ old('ends_on') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scheduled_start_time" class="form-label">Shift Start</label>
                            <input type="time" name="scheduled_start_time" id="scheduled_start_time" class="form-control"
                                value="{{ old('scheduled_start_time') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="scheduled_end_time" class="form-label">Shift End</label>
                            <input type="time" name="scheduled_end_time" id="scheduled_end_time" class="form-control"
                                value="{{ old('scheduled_end_time') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Assign Guard
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $("form").on('submit', function (e) {
            // e.preventDefault();
            $('button[type=submit]').attr('disabled', '');
        })
    </script>
@endpush