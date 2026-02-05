<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function send(string $phone, string $message): bool
    {
        // Normalisasi nomor: 08xxx → 628xxx
        $phone = preg_replace('/^0/', '62', $phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post(config('services.fonnte.endpoint'), [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            // log sukses
            Log::info('Fonnte WA Sent', [
                'phone' => $phone,
                'response' => $response->json(),
            ]);

            return $response->successful();

        } catch (\Throwable $e) {
            // log error
            Log::error('Fonnte WA Error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
