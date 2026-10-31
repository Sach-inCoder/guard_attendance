
@extends('layouts.app')

@section('title', 'Dashboard')
@push('style')
<style>

</style>
@endpush
@section('content')
    <div class="card border-dark mb-3 shadow-sm">
        <div class="card-header">
            <h6 class="card-title">
                My Attendance
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="month" class="form-label">Month</label>
                            <select
                                class="form-select"
                                name="month"
                                id="month"
                            >
                                <option value="">Jan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <select
                                class="form-select"
                                name="year"
                                id="year"
                            >
                                <option value="">Last Three Years</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site</label>
                            <select
                                class="form-select"
                                name="site_id"
                                id="site_id"
                            >
                                <option value="">Site One</option>
                                <option value="">Site Two</option>
                                <option value="">Site Three</option>
                            </select>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            Get Attendance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body"></div>
    </div>
@endsection
