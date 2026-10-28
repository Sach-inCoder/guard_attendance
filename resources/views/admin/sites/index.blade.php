@extends('layouts.admin_app')
@section('page-title', 'Sites')
@section('content')
    <div class="">
        <a class="btn btn-primary" href="{{ route('admin.sites.create') }}"><i class="fas fa-plus"></i> Add Site</a>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-hover  table-light">
                <thead class="table-light">
                    <caption>
                        Sites
                    </caption>
                    <tr>
                        <th>S.no</th>
                        <th>Company</th>
                        <th>Site Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($sites as $site)
                        <tr class="">
                            <td>{{ $sites->firstItem() + $loop->index }}</td>
                            <td scope="row">{{ $site->company['name'] }}</td>
                            <td scope="row">{{ $site->address }}</td>
                            <td><span
                                    class="badge text-bg-{{$site->is_active == 'active' ? 'success' : 'danger' }}">{{$site->is_active == 'active' ? 'Active' : 'Not Active' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sites.edit', $site) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($sites->hasPages())
            <div class="mt-3">
                {{ $sites->links() }}
            </div>
        @endif
    </div>
@endsection