@extends('layouts.admin_app')
@section('page-title', 'Companies')
@section('content')
    <div class="">
        <a class="btn btn-primary" href="{{ route('admin.companies.create') }}"><i class="fas fa-plus"></i> Add Company</a>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-hover  table-light">
                <thead class="table-light">
                    <caption>
                        Companies
                    </caption>
                    <tr>
                        <th>S.no</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($companies as $company)
                        <tr class="">
                            <td>{{ $companies->firstItem() + $loop->index }}</td>
                            <td scope="row">{{ $company->code }} | {{ $company->name }}</td>
                            <td><span
                                    class="badge text-bg-{{$company->is_active == 'active' ? 'success' : 'danger' }}">{{$company->is_active == 'active' ? 'Active' : 'Not Active' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($companies->hasPages())

            <div class="mt-3">
                {{ $companies->links() }}
            </div>

        @endif

    </div>
@endsection