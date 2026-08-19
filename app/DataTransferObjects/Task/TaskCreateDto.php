<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Task;

use App\Constants\CategoryEnum;

class TaskCreateDto
{
    private string $title;

    private CategoryEnum $category;

    public function __construct(string $title, CategoryEnum $category)
    {
        $this->title = $title;
        $this->category = $category;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): CategoryEnum
    {
        return $this->category;
    }
}
