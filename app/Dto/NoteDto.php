<?php
declare(strict_types=1);

namespace App\Dto;

class NoteDto
{
    private string $title;

    private string $body;

    private ?int $folderId;

    public function __construct(
        string $title,
        string $body,
        ?int $folderId = null
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->folderId = $folderId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getFolderId(): ?int
    {
        return $this->folderId;
    }
}
