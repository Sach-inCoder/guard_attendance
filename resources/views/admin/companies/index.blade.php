@extends('layouts.admin_app')
@section('page-title', 'Companies')
@section('content')
<div class="">
    <a class="btn btn-success btn-sm" href="">Add Company</a>
    <hr>
    <div
        class="table-responsive"
    >
        <table
            class="table table-striped table-hover  table-light"
        >
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
                @foreach ($companies as $guard)
                <tr
                    class=""
                >
                    <td>{{ $companies->firstItem() + $loop->index }}</td>
                    <td scope="row">{{ $guard->code }} | {{ $guard->name }}</td>
                    <td><span class="badge text-bg-{{$guard->is_active == 'active' ? 'success' : 'danger' }}">{{$guard->is_active == 'active' ? 'Active' : 'Not Active' }}</span></td>
                    <td>
                        <a href="" class="btn btn-success btn-sm">Edit</a>
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
