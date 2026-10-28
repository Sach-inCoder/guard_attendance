@extends('layouts.admin_app')
@section('page-title', 'Guards')
@section('content')
<div class="">
    <a class="btn btn-success btn-sm" href="{{ route('admin.guards.create') }}">Add Guard</a>
    <hr>
    <div
        class="table-responsive"
    >
        <table
            class="table table-striped table-hover  table-light"
        >
            <thead class="table-light">
                <caption>
                    Guards
                </caption>
                <tr>
                    <th>S.no</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($guards as $guard)
                <tr
                    class=""
                >
                    <td>{{ $guards->firstItem() + $loop->index }}</td>
                    <td scope="row">{{ $guard->employee_id }} | {{ $guard->full_name }}</td>
                    <td><span class="badge text-bg-{{$guard->employment_status == '1' ? 'success' : 'danger' }}">{{$guard->employment_status == 1 ? 'Active' : 'Not Active' }}</span></td>
                    <td>
                        <a href="{{ route('admin.guards.edit',$guard) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($guards->hasPages())

        <div class="mt-3">
            {{ $guards->links() }}
        </div>

    @endif
    
</div>
@endsection
