<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Note;

use App\Constants\Note\NoteCategoryEnum;

class NoteCreateDto
{
    private string $title;

    private string $content;

    private NoteCategoryEnum $category;

    public function __construct(
        string $title,
        string $content,
        NoteCategoryEnum $category
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCategory(): NoteCategoryEnum
    {
        return $this->category;
    }
}
