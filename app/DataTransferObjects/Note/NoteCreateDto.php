<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Note;

use App\Constants\CategoryEnum;

class NoteCreateDto
{
    private string $title;

    private string $content;

    private CategoryEnum $category;

    private ?string $folderId;

    public function __construct(
        string $title,
        string $content,
        CategoryEnum $category,
        ?string $folderId = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->folderId = $folderId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
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
