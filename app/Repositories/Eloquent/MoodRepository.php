<?php

namespace App\Repositories\Eloquent;

use App\Models\Mood;
use App\Repositories\Contracts\MoodRepositoryInterface;

class MoodRepository implements MoodRepositoryInterface
{
    public function getByUserId(int $userId)
    {
        return Mood::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function findByIdAndUserId(int $id, int $userId)
    {
        return Mood::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data)
    {
        return Mood::create($data);
    }

    public function update(int $id, array $data)
    {
        $mood = Mood::findOrFail($id);
        $mood->update($data);
        return $mood;
    }

    public function delete(int $id)
    {
        $mood = Mood::findOrFail($id);
        return $mood->delete();
    }
}
