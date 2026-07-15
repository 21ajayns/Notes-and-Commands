<?php
declare(strict_types=1);

namespace App\Dto;

class FolderDto
{
    private string $name;

    private ?string $image;

    public function __construct(
        string $name,
        ?string $image = null
    ) {
        $this->name = $name;
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
