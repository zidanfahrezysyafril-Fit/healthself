<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function history()
    {
        // Dummy data for chat history
        return response()->json([
            'status' => 'success',
            'data' => [
                [
                    'id' => '1',
                    'message' => 'Halo! Saya HealthSelf AI. Ada yang bisa saya bantu terkait kesehatan Anda?',
                    'is_user' => false,
                    'created_at' => now()->subMinutes(10)->toIso8601String(),
                ]
            ]
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = strtolower($request->message);
        
        $aiResponse = "Maaf, saya tidak mengerti. Bisa jelaskan lebih detail?";
        
        if (str_contains($message, 'stres') || str_contains($message, 'cemas')) {
            $aiResponse = "Tarik napas dalam-dalam. Cobalah teknik pernapasan 4-7-8 untuk menenangkan diri. Apakah ada hal spesifik yang membuat Anda merasa stres hari ini?";
        } else if (str_contains($message, 'tidur')) {
            $aiResponse = "Sulit tidur bisa disebabkan oleh banyak hal. Cobalah hindari kafein dan gadget setidaknya 1 jam sebelum tidur. Apakah Anda sudah mencoba mendengarkan musik relaksasi?";
        } else if (str_contains($message, 'halo') || str_contains($message, 'hai')) {
            $aiResponse = "Halo! Saya asisten AI HealthSelf Anda. Ada yang bisa saya bantu terkait kesehatan mental dan keseharian Anda hari ini?";
        } else if (str_contains($message, 'tips')) {
            $aiResponse = "Berikut beberapa tips untuk menjaga kesehatan mental:\n\n1. **Olahraga teratur**\n2. **Tidur cukup** 7-8 jam semalam\n3. **Makan makanan bergizi**\n4. Sempatkan waktu untuk **hobi** dan relaksasi.";
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => (string) (time()),
                'message' => $aiResponse,
                'is_user' => false,
                'created_at' => now()->toIso8601String(),
            ]
        ]);
    }
}
