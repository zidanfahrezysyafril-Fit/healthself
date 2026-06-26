<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthKnowledge extends Model
{
    protected $fillable = [
        'category',
        'question',
        'answer',
        'source',
        'embedding'
    ];
}