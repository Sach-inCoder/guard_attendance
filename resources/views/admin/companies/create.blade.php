@extends('layouts.admin_app')
@section('page-title', 'Create Company')
@section('content')
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Add Company</h4>
                        <p class="text-muted mb-0">
                            Create a new Company
                        </p>
                    </div>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                    
                    @include('admin.companies.form')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Company
                        </button>
                        <a href="{{ route('admin.companies.index') }}" class="btn btn-light">
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