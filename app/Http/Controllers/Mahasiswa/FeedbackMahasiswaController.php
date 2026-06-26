<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackMahasiswaController extends Controller
{
    public function create()
    {
        $hadFeedback = Feedback::where('id_user', auth()->id())->exists();
        return view('mahasiswa.feedback.create', compact('hadFeedback'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating'             => 'required|integer|min:1|max:5',
            'komentar'           => 'nullable|string|max:1000',
            'kategori_feedback'  => 'required|string|in:umum,chatbot,artikel,konselor',
        ]);

        Feedback::create([
            'id_user'           => auth()->id(),
            'rating'            => $request->rating,
            'komentar'          => $request->komentar,
            'kategori_feedback' => $request->kategori_feedback,
            'tanggal'           => now(),
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih atas feedback Anda! ❤️');
    }
}
