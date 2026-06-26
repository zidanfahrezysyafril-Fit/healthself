<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtikelKonselorController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with(['pembuat', 'kategori']);
        if ($request->filled('tab') && $request->tab === 'validasi') {
            $query->where('status', 'pending');
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        $artikels  = $query->latest()->paginate(12);
        $kategoris = Kategori::all();
        $pendingCount = Artikel::where('status', 'pending')->count();
        return view('konselor.artikel.index', compact('artikels', 'kategoris', 'pendingCount'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('konselor.artikel.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'isi_konten'  => 'required|string',
            'id_kategori' => 'required|exists:kategori,id',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        $data['id_user']        = auth()->id();
        $data['id_konselor']    = auth()->id(); // konselor sekaligus validator diri sendiri
        $data['status']         = 'published';  // langsung publish
        $data['tanggal_publish']= now();
        $data['slug']           = Str::slug($data['judul']) . '-' . uniqid();

        Artikel::create($data);
        return redirect()->route('konselor.artikel.index')->with('success', 'Artikel berhasil dipublikasikan!');
    }

    public function showValidasi(Artikel $artikel)
    {
        abort_if($artikel->status !== 'pending', 404);
        return view('konselor.artikel.validasi', compact('artikel'));
    }

    public function approve(Request $request, Artikel $artikel)
    {
        $artikel->update([
            'status'          => 'published',
            'id_konselor'     => auth()->id(),
            'tanggal_publish' => now(),
            'catatan_validasi'=> null,
        ]);
        return redirect()->route('konselor.artikel.index', ['tab' => 'validasi'])
                         ->with('success', 'Artikel berhasil disetujui dan dipublikasikan.');
    }

    public function reject(Request $request, Artikel $artikel)
    {
        $request->validate(['catatan_validasi' => 'required|string|min:10']);
        $artikel->update([
            'status'           => 'rejected',
            'id_konselor'      => auth()->id(),
            'catatan_validasi' => $request->catatan_validasi,
        ]);
        return redirect()->route('konselor.artikel.index', ['tab' => 'validasi'])
                         ->with('success', 'Artikel telah ditolak. Catatan dikirim ke admin.');
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
