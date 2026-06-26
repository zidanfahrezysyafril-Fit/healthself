<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtikelAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with(['pembuat', 'kategori', 'konselor']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        $artikels  = $query->latest()->paginate(12);
        $kategoris = Kategori::all();
        return view('admin.artikel.index', compact('artikels', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.artikel.create', compact('kategoris'));
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

        $data['id_user'] = auth()->id();
        $data['status']  = 'pending'; // menunggu verifikasi konselor
        $data['slug']    = Str::slug($data['judul']) . '-' . uniqid();

        Artikel::create($data);
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dibuat dan menunggu verifikasi konselor.');
    }

    public function show(Artikel $artikel)
    {
        return view('admin.artikel.show', compact('artikel'));
    }

    public function edit(Artikel $artikel)
    {
        $kategoris = Kategori::all();
        return view('admin.artikel.edit', compact('artikel', 'kategoris'));
    }

    public function update(Request $request, Artikel $artikel)
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

        // Jika artikel direject, edit ulang → kembali ke pending
        if ($artikel->status === 'rejected') {
            $data['status'] = 'pending';
            $data['catatan_validasi'] = null;
        }

        $artikel->update($data);
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
