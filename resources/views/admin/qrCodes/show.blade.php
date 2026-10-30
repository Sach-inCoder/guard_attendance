@extends('layouts.admin_app')
@section('page-title', 'QR Codes')
@section('content')
<div class="container py-4">
    <div class="card mx-auto shadow-sm" style="max-width: 550px;">
        <div class="card-body text-center p-4">
            <h3>{{ $qrcode->label }}</h3>
            <h5>{{ $qrcode->site->company['name'] }}</h5>
            <p class="text-secondary">
                Site: {{ $qrcode->site->address }}
            </p>

            <img
                src="{{ route('admin.qrcodes.image', $qrcode) }}"
                alt="Site QR Code"
                class="img-fluid border rounded p-2 mb-3"
                style="max-width: 350px;"
            >

            <p class="small text-secondary">
                QR ID: {{ $qrcode->public_id }}
            </p>

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.print()"
            >
                Print QR Code
            </button>

            <a
                href="{{ route('admin.qrcodes.index') }}"
                class="btn btn-outline-secondary"
            >
                Back to QR Codes
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    nav, .navbar, button, .btn, a {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .card {
        border: 0 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
