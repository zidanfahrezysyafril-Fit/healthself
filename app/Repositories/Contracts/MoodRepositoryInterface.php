<?php

namespace App\Repositories\Contracts;

interface MoodRepositoryInterface
{
    public function getByUserId(int $userId);
    public function findByIdAndUserId(int $id, int $userId);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
