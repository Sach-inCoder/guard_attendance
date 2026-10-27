@extends('layouts.admin_app')
@section('page-title', 'Guards')
@section('content')
<div class="">
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
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($guards as $guard)
                <tr
                    class=""
                >
                    <td>{{ $guards->firstItem() + $loop->index }}</td>
                    <td scope="row">{{ $guard->employee_id }} | {{ $guard->full_name }}</td>
                    <td><span class="badge text-bg-success">Active</span></td>
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
