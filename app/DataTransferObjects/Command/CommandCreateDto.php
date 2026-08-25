<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Command;

use App\Constants\CategoryEnum;

class CommandCreateDto
{
    private string $title;

    /** @var mixed[] */
    private array $rows;

    private CategoryEnum $category;

    private ?string $folderId;

    /**
     * @param mixed[] $rows
     */
    public function __construct(
        string $title,
        array $rows,
        CategoryEnum $category,
        ?string $folderId = null
    ) {
        $this->title = $title;
        $this->rows = $rows;
        $this->category = $category;
        $this->folderId = $folderId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return mixed[]
     */
    public function getRows(): array
    {
        return $this->rows;
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
