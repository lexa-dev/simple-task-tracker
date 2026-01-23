<?php

namespace App\Storage;

use App\Entity\Task;

interface TaskStorageInterface
{
    public function save(Task $task): void;
    public function find(int $id): ?Task;
    public function findAll(): array;
    public function delete(int $id): void;
}