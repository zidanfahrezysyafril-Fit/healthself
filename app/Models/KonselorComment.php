<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonselorComment extends Model
{
    protected $table = 'konselor_comments';
    protected $fillable = ['id_riwayat', 'id_konselor', 'id_user', 'komentar', 'email_sent', 'is_read'];

    protected $casts = [
        'email_sent' => 'boolean',
        'is_read'    => 'boolean',
    ];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatChat::class, 'id_riwayat');
    }

    public function konselor()
    {
        return $this->belongsTo(User::class, 'id_konselor');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
