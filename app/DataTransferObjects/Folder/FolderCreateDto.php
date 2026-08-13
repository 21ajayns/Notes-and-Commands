<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Folder;

class FolderCreateDto
{
    private string $name;

    private ?string $folderId;

    public function __construct(
        string $name,
        ?string $folderId = null
    ) {
        $this->name = $name;
        $this->folderId = $folderId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }
}