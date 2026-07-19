<?php
declare(strict_types=1);

namespace App\Dto;

class FolderDto
{
    private string $name;

    private ?string $parentId;

    private ?string $image;

    public function __construct(
        string $name,
        ?string $parentId = null,
        ?string $image = null
    ) {
        $this->name = $name;
        $this->parentId = $parentId;
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}