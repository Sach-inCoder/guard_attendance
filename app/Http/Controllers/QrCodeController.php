<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\QrCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
class QrCodeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer', 'exists:sites,id'],
            'label' => ['nullable', 'string', 'max:100'],
        ]);
        $existingQr = QrCode::where('site_id', $validated['site_id'])
            ->first();

        if ($existingQr) {
            return back()
                ->withInput()
                ->withErrors([
                    'site_id' => 'This site already has a QR code. Delete the existing QR code before generating a new one.',
                ]);
        }
        $plainToken = Str::random(64);
        $publicId = (string) Str::uuid();

        $qrCode = DB::transaction(function () use ($validated, $plainToken, $publicId) {
            return QrCode::create([
                'site_id' => $validated['site_id'],
                'public_id' => $publicId,
                'token_hash' => hash('sha256', $plainToken),
                'label' => $validated['label'] ?? 'Unit QR Code',
                'is_active' => true,
                'created_by' => Auth::id(),
            ]);
        });

        // The QR payload contains the public ID and secret token.
        $payload = url('/qr/scan')
            . '?id=' . urlencode($publicId)
            . '&token=' . urlencode($plainToken);

        // Generate the PNG through the external QR service.
        $response = Http::timeout(20)->get(
            'https://api.qrserver.com/v1/create-qr-code/',
            [
                'size' => '500x500',
                'format' => 'png',
                'data' => $payload,
            ]
        );

        if (!$response->successful()) {
            $qrCode->delete();

            return back()
                ->withInput()
                ->withErrors(['qr' => 'QR image generation failed. Please try again.']);
        }

        // Keep the image private, not in the public folder.
        Storage::disk('local')->put(
            'qr-codes/' . $publicId . '.png',
            $response->body()
        );

        return redirect()
            ->route('admin.qrcodes.show', $qrCode)
            ->with('success', 'QR code generated successfully.');
    }
    public function index()
    {
        $qrCodes = QrCode::with(['site', 'creator'])
            ->where('is_active', 1)
            ->latest()
            ->paginate(12);

        return view('admin.qrCodes.index', compact('qrCodes'));
    }
    public function create()
    {
        $sites = Site::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.qrCodes.create', compact('sites'));
    }

    public function show(QrCode $qrcode)
    {
        $qrcode->load(['site', 'creator']);

        return view('admin.qrCodes.show', compact('qrcode'));
    }
    public function image(QrCode $qrcode)
    {
        abort_unless(
            auth()->user()->role?->name === 'admin',
            403
        );

        $path = 'qr-codes/' . $qrcode->public_id . '.png';

        abort_unless(
            Storage::disk('local')->exists($path),
            404,
            'QR image not found.'
        );

        return response(
            Storage::disk('local')->get($path),
            200,
            ['Content-Type' => 'image/png']
        );
    }
    public function destroy(QrCode $qrcode)
    {
        // Delete saved QR image, if it exists
        $path = 'qr-codes/' . $qrcode->public_id . '.png';

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        $qrcode->delete();

        return redirect()
            ->route('admin.qrcodes.index')
            ->with('success', 'QR code deleted successfully. You can now generate a new QR code for this site.');
    }
}