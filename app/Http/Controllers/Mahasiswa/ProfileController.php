<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KonselorComment;
use App\Models\RiwayatChat;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $konselorComments = KonselorComment::where('id_user', $user->id)
                                            ->with(['konselor', 'riwayat'])
                                            ->latest()
                                            ->get();

        // Tandai semua komentar sebagai sudah dibaca
        KonselorComment::where('id_user', $user->id)
                        ->where('is_read', false)
                        ->update(['is_read' => true]);

        return view('mahasiswa.profile', compact('user', 'konselorComments'));
    }
}
