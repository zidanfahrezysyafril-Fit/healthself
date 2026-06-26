<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthData extends Model
{
    protected $fillable = [
        'disease',
        'symptoms'
    ];

    protected $casts = [
        'symptoms' => 'array'
    ];
}