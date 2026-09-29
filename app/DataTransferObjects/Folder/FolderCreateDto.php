<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Folder;

use App\Constants\CategoryEnum;

class FolderCreateDto
{
    private string $organizationId;

    private string $name;

    private CategoryEnum $category;

    private ?string $folderId;

    public function __construct(
        string $organizationId,
        string $name,
        CategoryEnum $category,
        ?string $folderId = null
    ) {
        $this->organizationId = $organizationId;
        $this->name = $name;
        $this->category = $category;
        $this->folderId = $folderId;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
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
