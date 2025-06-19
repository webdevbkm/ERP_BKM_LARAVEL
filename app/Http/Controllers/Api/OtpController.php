<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OtpController extends Controller
{
    // Mengirim OTP ke pelanggan
    public function send(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        $customer = Customer::find($request->customer_id);

        if (!$customer || !$customer->telepon) {
            return response()->json(['message' => 'Nomor telepon pelanggan tidak ditemukan.'], 404);
        }

        // Generate OTP 6 digit
        $otpCode = rand(100000, 999999);

        // Simpan OTP ke database, berlaku selama 5 menit
        OtpVerification::create([
            'customer_id' => $customer->id,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // =================================================================
        // TODO: INTEGRASI DENGAN SMS GATEWAY ANDA DI SINI
        // Contoh: $smsService->send($customer->telepon, "Kode OTP Anda: $otpCode");
        // Untuk sekarang, kita tampilkan di response untuk testing.
        // =================================================================

        return response()->json([
            'message' => 'OTP berhasil dikirim (untuk testing).',
            'testing_otp' => $otpCode // HAPUS INI DI PRODUKSI
        ]);
    }

    // Memverifikasi OTP yang dimasukkan
    public function verify(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'otp_code' => 'required|string|digits:6',
        ]);

        $otpRecord = OtpVerification::where('customer_id', $request->customer_id)
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('used_at')
            ->first();

        if ($otpRecord) {
            $otpRecord->update(['used_at' => Carbon::now()]);
            return response()->json(['success' => true, 'message' => 'OTP berhasil diverifikasi.']);
        }

        return response()->json(['success' => false, 'message' => 'OTP salah atau sudah kedaluwarsa.'], 400);
    }
}
