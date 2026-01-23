<?php

namespace App\Storage;

use App\Entity\Task;

class MemoryTaskStorage implements TaskStorageInterface
{
    /**
     * Array for task's storage
     *
     * @var array
     */
    private array $tasks = [];

    /**
     * Task autoIncrement for ID
     *
     * @var integer
     */
    private int $autoIncrement = 1;

    /**
     * Save one task
     *
     * @param Task $task
     * @return void
     */
    public function save(Task $task): void
    {
        $task->setId($this->autoIncrement);
        $this->tasks[$this->autoIncrement] = $task;
        $this->autoIncrement++;
    }

    /**
     * Find task by ID
     *
     * @param integer $id
     * @return Task
     */
    public function find(int $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    /**
     * Find all tasks
     *
     * @return array
     */
    public function findAll(): array
    {
        return array_values($this->tasks);
    }

    /**
     * Delete task by ID
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void
    {
        unset($this->tasks[$id]);
    }
}