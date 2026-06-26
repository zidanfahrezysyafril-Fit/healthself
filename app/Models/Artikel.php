<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    protected $table = 'artikel';

    protected $fillable = [
        'judul', 'slug', 'isi_konten', 'thumbnail',
        'id_kategori', 'id_user', 'id_konselor',
        'status', 'catatan_validasi', 'tanggal_publish',
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul) . '-' . uniqid();
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function konselor()
    {
        return $this->belongsTo(User::class, 'id_konselor');
    }

    public function isPublished(): bool { return $this->status === 'published'; }
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail) {
            return str_starts_with($this->thumbnail, 'http')
                ? $this->thumbnail
                : asset('storage/' . $this->thumbnail);
        }
        return 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=800&auto=format&fit=crop';
    }
}
