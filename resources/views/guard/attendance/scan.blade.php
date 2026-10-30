@extends('layouts.app')

@section('title', 'Scan Code')
@push('style')
    <style>

    </style>
@endpush
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-3">Site Attendance</h4>

            <p class="mb-1"><strong>Site:</strong> {{ $site->name }}</p>
            <p class="mb-1"><strong>Address:</strong> {{ $site->address }}</p>
            <p class="mb-3"><strong>Shift:</strong> {{ $shift->name }}</p>

            <div id="message" class="alert alert-info">
                Allow location access to continue.
            </div>

            <form method="POST" action="{{ route('guard.qr.attendance') }}"
                  id="attendanceForm">
                @csrf

                <input type="hidden" name="id" value="{{ $qr->public_id }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <button type="button" class="btn btn-primary w-100"
                        id="locationButton">
                    Verify Location & Continue
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const button = document.getElementById('locationButton');
const message = document.getElementById('message');

button.addEventListener('click', function () {
    if (!navigator.geolocation) {
        message.textContent = 'Your browser does not support location access.';
        return;
    }

    button.disabled = true;
    button.textContent = 'Getting location...';
    message.textContent = 'Please allow GPS access and wait.';

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById('latitude').value =
                position.coords.latitude;

            document.getElementById('longitude').value =
                position.coords.longitude;

            document.getElementById('attendanceForm').submit();
        },
        function () {
            message.textContent =
                'Location access failed. Enable GPS and allow browser location permission.';
            button.disabled = false;
            button.textContent = 'Try Again';
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
});
</script>
@endpush