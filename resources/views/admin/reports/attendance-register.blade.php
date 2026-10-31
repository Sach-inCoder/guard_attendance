@extends('layouts.admin_app')
<style>
    .register-paper {
        position: relative;
        background: #fff;
        padding: 20px;
        overflow-x: auto;
    }
    .register-paper .logo{
        position: absolute;
        top: 20px;
        right:20px;
    }
    .register-title {
        text-align: center;
        font-weight: 800;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .register-table {
        width: 100%;
        min-width: 1250px;
        border-collapse: collapse;
        font-size: 11px;
        color: #000;
    }

    .register-table th,
    .register-table td {
        border: 1px solid #000;
        padding: 5px 3px;
        text-align: center;
        vertical-align: middle;
    }

    .register-table thead th {
        font-weight: 700;
    }

    .register-table .guard-name {
        text-align: left;
        min-width: 130px;
    }

    .register-table .employee-id {
        min-width: 65px;
    }

    .register-table .signature-column {
        min-width: 100px;
    }

    .register-header-line {
        border-bottom: 1px solid #000;
        display: inline-block;
        min-width: 150px;
        height: 20px;
    }

    .register-footer {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-top: 55px;
        text-align: center;
    }

    .signature-box {
        flex: 1;
        border-top: 1px solid #000;
        padding-top: 8px;
        font-size: 11px;
        min-width: 100px;
    }

    .print-only {
        display: none;
    }

    @media print {
        @page {
            size: A3 landscape;
            margin: 8mm;
        }

        body {
            background: #fff !important;
        }

        body * {
            visibility: hidden;
        }

        #printRegister,
        #printRegister * {
            visibility: visible;
        }

        #printRegister {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .print-only {
            display: block;
        }

        .register-paper {
            padding: 0;
            overflow: visible;
        }

        .register-table {
            min-width: 0;
            width: 100%;
            font-size: 8px;
        }

        .register-table th,
        .register-table td {
            padding: 3px 2px;
        }

        .register-table tr {
            page-break-inside: avoid;
        }

        .register-title {
            font-size: 19px;
        }

        .register-footer {
            margin-top: 35px;
        }
    }
</style>
@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Attendance Register</h4>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.reports.attendance-register') }}" class="card card-body mb-4">
            
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Company</label>
                    <select name="company_id" class="form-select">
                        <option value="">Select Company</option>
                        @foreach($companies as $item)
                            <option value="{{ $item->id }}" @selected(request('company_id') == $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Site</label>
                    <select name="site_id" class="form-select">
                        <option value="">Select Site</option>
                        @foreach($sites as $item)
                            <option value="{{ $item->id }}" @selected(request('site_id') == $item->id)>
                                {{ $item->name }} | {{ $item->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Month</label>
                    <select name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected($month == $i)>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Year</label>
                    <select name="year" class="form-select">
                        @for($i = now()->year - 2; $i <= now()->year + 1; $i++)
                            <option value="{{ $i }}" @selected($year == $i)>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Show Details
                    </button>
                </div>

            </div>
        </form>

        {{-- Attendance Details --}}
        @if($site)
            <div class="d-flex justify-content-end mb-3 no-print">
                <button type="button" onclick="window.print()" class="btn btn-dark">
                    <i class="bi bi-printer me-1"></i> Print Register
                </button>
            </div>
            <div class="register-paper" id="printRegister">
                <div class="logo">
                    <img src="{{ asset('assets/images/favicon.png') }}" width="120" height="120" alt="">
                </div>
                <div class="text-center mb-3">
                    <h2 class="fw-bold mt-2">
                        PRO SHIELD SECURITY SOLUTIONS
                    </h2>
                    <div class="register-title">
                        <B>A-202, SECTOR 22, NOIDA-201301,(U.P.) OPP. NOIDA STADIUM</B>
                    </div>

                    <div>
                        E-mail : info info_proshield@yahoo.com, deepak@proshieldsecuritysolutions.in, Website :
                        www.proshieldsecuritysolutions.in
                    </div>

                </div>
                {{-- <div class="mb-3"> --}}
                    {{-- <h6>UNIT NAME {{ $company->name ?? '' }}</h6> --}}
                    {{-- <h6>UNIT ADDRESS {{ $site->address ?? '' }}</h6> --}}
                    {{-- <p class="mb-1">{{ $site->name }}</p> --}}
                    {{-- <p class="mb-1">{{ $site->address }}</p> --}}

                    {{-- </div> --}}
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>UNIT NAME:</strong>
                        {{ $company->name }}
                    </div>

                    <div class="col-6 text-end">
                        {{-- <strong>INVOICE NO.:</strong> --}}
                        {{-- <span class="register-header-line"></span> --}}
                    </div>

                    <div class="col-6 mt-2">
                        <strong>UNIT ADDRESS:</strong>
                        {{ $site->address }}
                    </div>

                    <div class="col-6 text-end mt-2">
                        <strong>MONTH / YEAR:</strong>
                        <strong>{{ date('F', mktime(0, 0, 0, $month, 1)) }}/{{ $year }}</strong>
                    </div>

                    <div class="col-12 mt-2">
                        <strong>NAME OF AREA OFFICER:</strong>
                        <span class="register-header-line"></span>
                    </div>
                </div>

                <div class="">
                    <table class="register-table">

                        <thead class="">
                            <tr>
                                <th rowspan="2">S.No.</th>
                                <th rowspan="2">ID No.</th>
                                <th rowspan="2">Rank</th>
                                <th rowspan="2">NAME</th>

                                <th colspan="{{ $daysInMonth }}">
                                    MONTHLY ATTENDANCE
                                </th>

                                <th rowspan="2">TOTAL DUTY</th>
                                <th rowspan="2" class="signature-column">
                                    EMPLOYEE SIGNATURE
                                </th>
                            </tr>
                            <tr>
                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    <th>{{ $day }}</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($register as $index => $guard)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="employee-id">
                                        {{ $guard['employee_id'] }}
                                    </td>

                                    <td></td>

                                    <td class="guard-name">
                                        {{ $guard['name'] }}
                                    </td>

                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <td>{{ $guard['days'][$day] }}</td>
                                    @endfor

                                    <td>
                                        {{ number_format($guard['total_duty'], 1) }}
                                    </td>

                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $daysInMonth + 6 }}" class="py-4">
                                        No guards assigned to this site during this month.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    {{-- Attendance Legend --}}
                    <div class="mt-3 small">
                        <strong>NOTE:</strong>
                        P - Present &nbsp; | &nbsp;
                        L - Late &nbsp; | &nbsp;
                        HD - Half Day &nbsp; | &nbsp;
                        A - Absent &nbsp; | &nbsp;
                        WO - Weekly Off &nbsp; | &nbsp;
                        H - Holiday
                    </div>
                    {{-- Signature Footer --}}
                    <div class="register-footer">

                        <div class="signature-box">
                            Signature of Supervisor
                        </div>

                        <div class="signature-box">
                            Signature of Field Officer
                        </div>

                        <div class="signature-box">
                            Checked By Client
                        </div>

                        <div class="signature-box">
                            Signature of Account Officer and HR
                        </div>

                        <div class="signature-box">
                            Signature of Director of Operation
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     const companySelect = document.getElementById('company_id');
        //     const siteSelect = document.getElementById('site_id');

        //     function filterSites() {

        //         const companyId = companySelect.value;

        //         Array.from(siteSelect.options).forEach(option => {

        //             if (!option.value) {
        //                 return;
        //             }

        //             const visible =
        //                 !companyId || option.dataset.company === companyId;

        //             option.hidden = !visible;

        //             if (!visible && option.selected) {
        //                 siteSelect.value = '';
        //             }
        //         });
        //     }
        //     companySelect.addEventListener('change', filterSites);
        //     filterSites();
        // });
    </script>
@endsection