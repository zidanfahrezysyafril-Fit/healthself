<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatChat extends Model
{
    protected $table = 'riwayat_chat';
    protected $fillable = ['id_user', 'pesan_user', 'respon_bot', 'is_flagged', 'flag_reason', 'waktu_chat'];

    protected $casts = [
        'is_flagged'  => 'boolean',
        'waktu_chat'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(KonselorComment::class, 'id_riwayat');
    }
}
