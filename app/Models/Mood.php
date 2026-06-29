<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mood',
        'note',
        'activity',
        'sleep_hours',
        'stress_level',
    ];

    protected $casts = [
        'activity' => 'array',
        'sleep_hours' => 'float',
        'stress_level' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
