<?php

namespace App\Storage;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Storage\TaskStorageInterface;

class FileTaskStorage implements TaskStorageInterface
{
    private string $filePath;
    private array $tasks = [];
    private int $autoIncrement = 1;

    public function __construct(string $filePath = __DIR__ . 'tasks.json')
    {
        $this->filePath = $filePath;
        $this->load();
    }

    private function load(): void
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }

        $data = json_decode(file_get_contents($this->filePath), true);
        if ($data === null) {
            $data = [];
        }

        $this->tasks = [];
        foreach ($data as $item) {
            $task = new Task($item['title'], $item['description']);
            $task->setId($item['id']);
            $task->setStatus(TaskStatus::from($item['status']));

            $this->tasks[$item['id']] = $task;
            if ($item['id'] >= $this->autoIncrement) {
                $this->autoIncrement = $item['id'] + 1;
            }
        }
    }

    private function saveToFile(): void
    {
        $data = array_map(fn(Task $task) => $task->toArray(), $this->tasks);
        file_put_contents($this->filePath, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    public function save(Task $task): void
    {
        if (!$task->getId() || $task->getId() <= 0) {
            $task->setId($this->autoIncrement++);
        }
        $this->tasks[$task->getId()] = $task;
        $this->saveToFile();
    }

    public function find(int $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->tasks);
    }

    public function delete(int $id): void
    {
        unset($this->tasks[$id]);
        $this->saveToFile();
    }
}