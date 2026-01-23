<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use DateTimeImmutable;

class Task
{
    private int $id = 0;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    /**
     * @param string $title
     * @param string $description
     */
    public function __construct(string $title, string  $description)
    {   

        $this->title = $title;
        $this->description = $description;  
        $this->status = TaskStatus::NEW;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = $this->created_at;
    }

    /**
     * Get task id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set task ID
     *
     * @param integer $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get task title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set task title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get task description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set task description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    /**
     * Get task status
     *
     * @return TaskStatus
     */
    public function geStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * Set task status
     *
     * @param TaskStatus $status
     * @return void
     */
    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Get task created_at value
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set task created_at value
     *
     * @param DateTimeImmutable $created_at
     * @return void
     */
    public function setCreatedAt(DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Get task updated_at value
     *
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Get task updated_at value
     *
     * @param DateTimeImmutable $updated_at
     * @return void
     */
    public function setUpdatedAt(DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}