<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Storage\MemoryTaskStorage;
use App\Storage\TaskStorageInterface;
use DateTimeImmutable;
use InvalidArgumentException;

class TaskManager
{
    /**
     * @param MemoryTaskStorage $storage
     */
    public function __construct(
        private readonly TaskStorageInterface $storage
    ) {}

    /**
     * Create task
     *
     * @param string $title
     * @param string $description
     * @return Task
     */
    public function create(string $title, string $description): ?Task
    {
        $newTask = new Task($title, $description);
        $this->storage->save($newTask);

        return $newTask;
    }

    /**
     * Update task
     *
     * @param integer|null $id
     * @param array|null $data
     * @return Task|null
     */
    public function update(?int $id, ?array $data): Task
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Task ID must be a positive integer');
        }

        if (empty($data)) {
            throw new InvalidArgumentException('Task data can not be empty');
        }

        $task = $this->storage->find($id);

        if (!$task) {
            throw new InvalidArgumentException('Task not found');
        }

        foreach ($data as $property => $value) {
            
            match ($property) {
                'title' => $task->setTitle($value),
                'description' => $task->setDescription($value),
                'status' => $task->setStatus(TaskStatus::from($value)),
                default => throw new \InvalidArgumentException("Unknown property: $property"),
            };
        }

        $task->setUpdatedAt(new DateTimeImmutable());
        $this->storage->save($task);

        return $task;
    }

    /**
     * Change task status
     *
     * @param integer $id
     * @param TaskStatus $status
     * @return void
     */
    public function changeStatus(int $id, TaskStatus $status): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Task ID must be a positive integer');
        }

        $task = $this->storage->find($id);

        if (!$task) {
            throw new InvalidArgumentException('Task not found');
        }

        $task->setStatus($status);
        $this->storage->save($task);
    }

    /**
     * Delete task by ID
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Task ID must be a positive integer');
        }

        $this->storage->delete($id);

        return [
            'Task with ID - ' . $id . 'deleted'
        ];
    }

    /**
     * Get task by ID
     *
     * @param integer $id
     * @return Task
     */
    public function get(int $id): Task
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Task ID must be a positive integer');
        }

        $task = $this->storage->find($id);

        if (!$task) {
            throw new InvalidArgumentException('Task not found');
        }

        return $task;
    }

    /** Get all tasks */
    public function getAll(): array
    {
        return $this->storage->findAll();
    }
}