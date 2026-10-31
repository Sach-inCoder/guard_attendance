@extends('layouts.app')

@section('title', 'Dashboard')
@push('style')
    <style>

    </style>
@endpush
@section('content')
    <div class="card border-dark">
        <div class="card-header">
            <h6 class="card-title">Welcome {{ auth()->user()->guardProfile?->full_name }}</h6>
        </div>
        <div class="card-body">
            <p class="card-text">
                MY ID : <b>PSS - {{ auth()->user()->guardProfile?->employee_id ?? 'N/A' }}</b>
            </p>
            <p class="card-text">
                Joining : <b>{{ auth()->user()->guardProfile?->joining_date ?? 'N/A' }}</b>
            </p>
            @if ($assignment)
                <p class="card-text">
                    Duty On:
                    <b>{{ $assignment?->site?->company['name'] . ' | ' . $assignment?->site?->name ?? 'Not Assigned' }}</b>
                </p>
                <p class="card-text">
                    Address:
                    <b>{{ $assignment?->site->address }}</b>
                </p>

                <p class="card-text">
                    Site Assigned By:
                    <b>{{ $assignment?->assignedBy?->name ?? 'N/A' }}</b>
                </p>
            @else
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <li>You have not assigned to Any Unit</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
    @if($attendance && $attendance->check_in_at && !$attendance->check_out_at)

        <div class="alert alert-success mt-3">
            <strong>Checked In:</strong>
            {{ \Carbon\Carbon::parse($attendance->check_in_at)->format('h:i A') }}
        </div>

    @elseif($attendance && $attendance->check_out_at)

        <div class="alert alert-success mt-3">
            <h5>Attendance Completed!</h5>

            <p class="mb-1">
                Check In:
                {{ \Carbon\Carbon::parse($attendance->check_in_at)->format('h:i A') }}
            </p>

            <p class="mb-1">
                Check Out:
                {{ \Carbon\Carbon::parse($attendance->check_out_at)->format('h:i A') }}
            </p>

            <p class="mb-0">
                Worked:
                {{ intdiv($attendance->worked_minutes, 60) }}h
                {{ $attendance->worked_minutes % 60 }}m
            </p>
        </div>

    @endif
    @if ($assignment)
        <div class="text-center py-5">
            @if(!$attendance)
                {{-- Not checked in --}}
                <button type="button" class="btn btn-outline-primary btn-lg" id="openScanner">
                    <i class="fas fa-qrcode"></i> Scan QR To Check in
                </button>

            @elseif($attendance->check_in_at && !$attendance->check_out_at)
                {{-- Checked in --}}
                <button type="button" class="btn btn-outline-danger btn-lg" id="openScanner">
                    <i class="fas fa-qrcode"></i> Scan QR To Check out
                </button>

            @else
                {{-- Completed --}}
                <button type="button" class="btn btn-success btn-lg" disabled>
                    Attendance Completed
                </button>
            @endif
        </div>
    @endif
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
            let currentPosition = null;

            function showMessage(message, type = 'info') {
                scanMessage.className = `alert alert-${type} mt-3`;
                scanMessage.textContent = message;
            }

            async function stopScanner() {
                if (html5QrCode) {
                    try {
                        if (html5QrCode.isScanning) {
                            await html5QrCode.stop();
                        }
                        html5QrCode.clear();
                    } catch (error) {
                        console.log(error);
                    }
                }

                html5QrCode = null;
            }

            function getLocation() {
                return new Promise((resolve, reject) => {

                    if (!navigator.geolocation) {
                        reject(new Error('GPS is not supported by your browser.'));
                        return;
                    }

                    let watchId;
                    let bestPosition = null;

                    const timeout = setTimeout(() => {
                        navigator.geolocation.clearWatch(watchId);

                        if (bestPosition) {
                            resolve(bestPosition);
                        } else {
                            reject(new Error('Unable to get your location. Please try again.'));
                        }
                    }, 30000);

                    watchId = navigator.geolocation.watchPosition(
                        function (position) {

                            const accuracy = position.coords.accuracy;

                            // Keep the most accurate reading.
                            if (
                                !bestPosition ||
                                accuracy < bestPosition.coords.accuracy
                            ) {
                                bestPosition = position;
                            }

                            showMessage(
                                `Finding accurate location... Accuracy: ${Math.round(accuracy)} metres`
                            );

                            // Accept only accuracy within 25 metres.
                            if (accuracy <= 25) {
                                clearTimeout(timeout);
                                navigator.geolocation.clearWatch(watchId);
                                resolve(position);
                            }
                        },

                        function (error) {
                            clearTimeout(timeout);

                            if (watchId !== undefined) {
                                navigator.geolocation.clearWatch(watchId);
                            }

                            reject(new Error(
                                'Unable to get location. Please enable location services.'
                            ));
                        },

                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 30000
                        }
                    );
                });
            }

            // Check In button
            openButton.addEventListener('click', async function () {

                if (!window.isSecureContext) {
                    showMessage(
                        'Camera and GPS require HTTPS. Open the website using a secure HTTPS URL.',
                        'danger'
                    );
                    return;
                }

                scannerSection.style.display = 'block';
                openButton.disabled = true;
                openButton.innerText = 'Checking in';
                processing = false;
                currentPosition = null;

                try {
                    // Getting GPS location
                    showMessage('Requesting location permission...');

                    currentPosition = await getLocation();

                    // Then open camera and start scanning
                    showMessage('Location captured. Opening camera...');

                    html5QrCode = new Html5Qrcode('qr-reader');

                    await html5QrCode.start(
                        { facingMode: 'environment' },
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        async function (decodedText) {

                            if (processing) return;

                            processing = true;

                            showMessage('QR detected! Submitting attendance...', 'success');

                            await stopScanner();

                            processQRCode(decodedText);
                        },
                        function () { }
                    );

                    showMessage('Camera ready. Scan your site QR code.', 'success');

                } catch (error) {

                    await stopScanner();

                    processing = false;
                    openButton.disabled = false;
                    openButton.innerText = 'Check in';

                    showMessage(error.message, 'danger');

                    // Show browser settings guidance for denied permissions
                    if (
                        error.message.toLowerCase().includes('permission denied') ||
                        error.name === 'NotAllowedError'
                    ) {
                        showMessage(
                            error.message +
                            ' Tap the lock/site settings icon beside the address bar, open Permissions, and allow Camera and Location.',
                            'danger'
                        );
                    }
                }
            });
            // Process QR code
            async function processQRCode(decodedText) {
                try {
                    if (!currentPosition) {
                        throw new Error('Location permission is required.');
                    }
                    const qrUrl = new URL(decodedText);
                    if (!qrUrl.pathname.endsWith('/qr/scan')) {
                        throw new Error('Invalid site QR code.');
                    }
                    const id = qrUrl.searchParams.get('id');
                    const token = qrUrl.searchParams.get('token');
                    if (!id || !token) {
                        throw new Error('Invalid QR code details.');
                    }
                    const latitude = currentPosition.coords.latitude;
                    const longitude = currentPosition.coords.longitude;
                    const accuracy = Math.round(currentPosition.coords.accuracy);
                    document.getElementById('qr_id').value = id;
                    document.getElementById('qr_token').value = token;
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    document.getElementById('accuracy').value = accuracy;
                    showMessage('Submitting attendance...', 'success');
                    document.getElementById('attendanceForm').submit();
                } catch (error) {

                    processing = false;
                    openButton.disabled = false;
                    openButton.innerText = 'Check in';
                    showMessage(error.message, 'danger');
                }
            }
            // Cancel scanner
            closeButton.addEventListener('click', async function () {
                await stopScanner();
                scannerSection.style.display = 'none';
                openButton.disabled = false;
                openButton.innerText = 'Check in';
                processing = false;
                currentPosition = null;
            });
        });
    </script>
@endpush