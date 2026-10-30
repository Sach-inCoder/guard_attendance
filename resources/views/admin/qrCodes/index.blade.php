@extends('layouts.admin_app')
@section('page-title', 'QR Codes')
@section('content')
<div class="">
    <a class="btn btn-success btn-sm" href="{{ route('admin.qrcodes.create') }}">Add QR Code</a>
    <hr>
    <div
        class="table-responsive"
    >
        <table
            class="table table-striped table-hover  table-light"
        >
            <thead class="table-light">
                <caption>
                    QR Codes
                </caption>
                <tr>
                    <th>S.no</th>
                    <th>Unit Name</th>
                    <th>SITE Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($qrCodes as $qrCode)
                <tr
                    class=""
                >
                    <td>{{ $qrCodes->firstItem() + $loop->index }}</td>
                    <td scope="row">
                        {{ $qrCode->site->company['name'] }}
                    </td>
                    <td scope="row">
                        {{ $qrCode->site['address'] }} | Created By {{ $qrCode->creator['name'] }}
                    </td>
                    <td><span class="badge text-bg-{{$qrCode->is_active == '1' ? 'success' : 'danger' }}">{{$qrCode->is_active == 1 ? 'Active' : 'Not Active' }}</span></td>
                    <td>
                        <form action="{{ route('admin.qrcodes.destroy',$qrCode) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Do yo want to delete this QR?')" type="submit"  class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        <a href="{{route('admin.qrcodes.show',$qrCode)}}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-qrcode"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($qrCodes->hasPages())

        <div class="mt-3">
            {{ $qrCodes->links() }}
        </div>

    @endif
    
</div>
@endsection
