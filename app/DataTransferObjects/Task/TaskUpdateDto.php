<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Task;

use App\Constants\TaskStatusEnum;

class TaskUpdateDto
{
    private ?string $title;

    private ?TaskStatusEnum $status;

    public function __construct(?string $title, ?TaskStatusEnum $status)
    {
        $this->title = $title;
        $this->status = $status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status;
    }
}
