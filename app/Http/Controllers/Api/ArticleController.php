<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with(['kategori', 'pembuat'])
            ->where('status', 'published');

        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != 'Semua' && $request->category != '') {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', $request->category);
            });
        }

        $articles = $query->latest('tanggal_publish')->paginate(10);

        // Transform collection to include full URL and author name
        $articles->getCollection()->transform(function ($artikel) {
            return [
                'id' => (string) $artikel->id,
                'title' => $artikel->judul,
                'category' => $artikel->kategori->nama_kategori ?? 'Kesehatan',
                'image_url' => $artikel->thumbnailUrl(),
                'author' => $artikel->pembuat->name ?? 'Admin',
                'date' => $artikel->tanggal_publish ? $artikel->tanggal_publish->format('d M Y') : $artikel->created_at->format('d M Y'),
                'content' => $artikel->isi_konten,
                'slug' => $artikel->slug,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $articles->items(),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ]
        ]);
    }

    public function show($slug)
    {
        $artikel = Artikel::with(['kategori', 'pembuat'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$artikel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => (string) $artikel->id,
                'title' => $artikel->judul,
                'category' => $artikel->kategori->nama_kategori ?? 'Kesehatan',
                'image_url' => $artikel->thumbnailUrl(),
                'author' => $artikel->pembuat->name ?? 'Admin',
                'date' => $artikel->tanggal_publish ? $artikel->tanggal_publish->format('d M Y') : $artikel->created_at->format('d M Y'),
                'content' => $artikel->isi_konten,
                'slug' => $artikel->slug,
            ]
        ]);
    }
}
