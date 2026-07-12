<?php
declare(strict_types=1);

namespace App\Dto;

class NoteDto
{
    private string $title;

    private string $body;

    private string $category;

    public function __construct(
        string $title,
        string $body,
        string $category
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->category = $category;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
