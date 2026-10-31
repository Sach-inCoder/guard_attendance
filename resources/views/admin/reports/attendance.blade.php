@extends('layouts.admin_app')
@section('page-title', 'Reports | Attendance')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header">
                <h3>Attendance Report</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.attendance-register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_id" class="form-label">Company</label>
                                <select class="form-select" name="company_id" id="company_id" required>
                                    <option value="">All Companies</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_id" class="form-label">Site</label>
                                <select class="form-select" name="site_id" id="site_id" required>
                                    <option value="">All Site Addresses</option>
                                    @foreach ($sites as $site)
                                        <option value="{{ $site->id }}">{{ $site->name }} | {{$site->address}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-select" required>
                                    <option value="">All Months</option>
                                    @foreach (range(1,12) as $item)
                                    <option value="{{ $item }}" @selected((string)$month == (string)$item)>
                                        {{ \Carbon\Carbon::create()->month($item)->format('F') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-select">
                                    <option value="">All years</option>
                                    @foreach ($years as $item)
                                    <option value="{{ $item }}" @selected($year == $item)>
                                        {{ $item }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary">
                                Get Attendance Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <pre>
            
        </pre>
    </div>
@endsection