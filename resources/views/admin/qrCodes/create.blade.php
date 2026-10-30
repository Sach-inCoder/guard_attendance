@extends('layouts.admin_app')
@section('page-title', 'Create QR CODE')
@section('content')
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Add QR CODE</h4>
                        <p class="text-muted mb-0">
                            Create a new QR CODE
                        </p>
                    </div>
                    <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.qrcodes.store') }}" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="site_id" class="form-label">Select SITE</label>
                        <select
                            class="form-select"
                            name="site_id"
                            id="site_id"
                        >
                        @foreach ($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->company['name'] . ' | ' . $site->address }}</option>
                        @endforeach
                        </select>
                    </div>
                    
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-qrcode"></i>
                            GENRATE QR CODE
                        </button>
                        <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $("form").on('submit',function (e) {
        // e.preventDefault();
        $('button[type=submit]').attr('disabled','');
    })
</script>
@endpush