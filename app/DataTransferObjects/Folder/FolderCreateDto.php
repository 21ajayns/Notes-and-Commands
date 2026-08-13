<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Folder;

use App\Constants\CategoryEnum;

class FolderCreateDto
{
    private string $name;

    private CategoryEnum $category;

    private ?string $folderId;

    public function __construct(
        string $name,
        CategoryEnum $category,
        ?string $folderId = null
    ) {
        $this->name = $name;
        $this->category = $category;
        $this->folderId = $folderId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): CategoryEnum
    {
        return $this->category;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }
}
