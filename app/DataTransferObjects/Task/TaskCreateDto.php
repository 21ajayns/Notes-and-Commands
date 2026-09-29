<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Task;

use App\Constants\CategoryEnum;

class TaskCreateDto
{
    private string $organizationId;

    private string $title;

    private CategoryEnum $category;

    public function __construct(string $organizationId, string $title, CategoryEnum $category)
    {
        $this->organizationId = $organizationId;
        $this->title = $title;
        $this->category = $category;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
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
