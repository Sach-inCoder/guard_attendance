@extends('layouts.app')

@section('title', 'Dashboard')
@push('style')
    <style>

    </style>
@endpush
@php
    $guard = auth()->user()->guardProfile;

    $assignment = $guard?->assignments()
        ->with(['site', 'assignedBy'])
        ->where('status', 'active')
        ->whereDate('starts_on', '<=', today())
        ->where(function ($query) {
            $query->whereNull('ends_on')
                ->orWhereDate('ends_on', '>=', today());
        })
        ->latest('id')
        ->first();
@endphp
@section('content')
    <div class="card border-dark">
        <div class="card-header">
            <h6 class="card-title">Dashboard</h6>
        </div>
        <div class="card-body">
            <p class="card-text">
                MY ID : <b>{{ auth()->user()->guardProfile?->employee_id ?? 'N/A' }}</b>
            </p>
            <p class="card-text">
                Joining : <b>{{ auth()->user()->guardProfile?->joining_date ?? 'N/A' }}</b>
            </p>
            <p class="card-text">
                Duty On:
                <b>{{ $assignment?->site?->name ?? 'Not Assigned' }}</b>
            </p>
            <p class="card-text">
                Address:
                <b>{{ $assignment?->site->address }}</b>
            </p>

            <p class="card-text">
                Site Assigned By:
                <b>{{ $assignment?->assignedBy?->name ?? 'N/A' }}</b>
            </p>
        </div>
    </div>
    <div class="text-center py-5">
        <button class="btn btn-lg btn-outline-success" id="openScanner">
            Check In
        </button>
    </div>
    <div id="scannerSection" class="mt-4" style="display:none;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Scan Site QR Code</h5>

                <div id="qr-reader" style="width:100%;"></div>

                <div id="scanMessage" class="alert alert-info mt-3">
                    Initializing camera...
                </div>

                <button type="button" class="btn btn-secondary w-100" id="closeScanner">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <form id="attendanceForm" action="{{ route('guard.qr.attendance') }}" method="POST" style="display:none;">
        @csrf

        <input type="hidden" name="id" id="qr_id">
        <input type="hidden" name="token" id="qr_token">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="accuracy" id="accuracy">
    </form>
@endsection
@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const openButton = document.getElementById('openScanner');
    const closeButton = document.getElementById('closeScanner');
    const scannerSection = document.getElementById('scannerSection');
    const scanMessage = document.getElementById('scanMessage');

    let html5QrCode = null;
    let processing = false;

    // Open camera
    openButton.addEventListener('click', async function () {

        scannerSection.style.display = 'block';
        openButton.disabled = true;

        scanMessage.className = 'alert alert-info mt-3';
        scanMessage.textContent = 'Opening camera...';

        try {
            html5QrCode = new Html5Qrcode('qr-reader');

            await html5QrCode.start(
                { facingMode: 'environment' },
                {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                async function (decodedText) {

                    if (processing) return;
                    processing = true;

                    scanMessage.textContent = 'QR detected. Checking...';

                    await html5QrCode.stop();

                    processQRCode(decodedText);
                },
                function () {
                    // Ignore continuous scan errors.
                }
            );

            scanMessage.textContent =
                'Camera ready. Point it towards the site QR code.';

        } catch (error) {

            scanMessage.className = 'alert alert-danger mt-3';
            scanMessage.textContent =
                'Unable to access camera. Allow camera permission and use HTTPS.';

            openButton.disabled = false;
            html5QrCode = null;
        }
    });

    // Process scanned QR
    async function processQRCode(decodedText) {

        try {

            const qrUrl = new URL(decodedText);

            if (!qrUrl.pathname.endsWith('/qr/scan')) {
                throw new Error('Invalid site QR code.');
            }

            const id = qrUrl.searchParams.get('id');
            const token = qrUrl.searchParams.get('token');

            if (!id || !token) {
                throw new Error('QR code is missing required information.');
            }

            scanMessage.className = 'alert alert-info mt-3';
            scanMessage.textContent = 'Getting your current location...';

            if (!navigator.geolocation) {
                throw new Error('GPS is not supported by this browser.');
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {

                    document.getElementById('qr_id').value = id;
                    document.getElementById('qr_token').value = token;

                    document.getElementById('latitude').value =
                        position.coords.latitude;

                    document.getElementById('longitude').value =
                        position.coords.longitude;
                    document.getElementById('accuracy').value = Math.round(position.coords.accuracy);

                    scanMessage.className = 'alert alert-success mt-3';
                    scanMessage.textContent =
                        'Location captured. Submitting attendance...';

                    document.getElementById('attendanceForm').submit();
                },
                function (error) {

                    processing = false;
                    openButton.disabled = false;

                    scanMessage.className = 'alert alert-danger mt-3';
                    scanMessage.textContent =
                        'Unable to get location. Enable GPS and allow location permission.';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 20000,
                    maximumAge: 0
                }
            );

        } catch (error) {

            processing = false;
            openButton.disabled = false;

            scanMessage.className = 'alert alert-danger mt-3';
            scanMessage.textContent = error.message;
        }
    }

    // Close camera
    closeButton.addEventListener('click', async function () {

        if (html5QrCode) {
            try {
                await html5QrCode.stop();
                html5QrCode.clear();
            } catch (error) {
                console.log(error);
            }
        }

        html5QrCode = null;
        processing = false;

        scannerSection.style.display = 'none';
        openButton.disabled = false;
    });

});
</script>
@endpush