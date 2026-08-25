<?php
declare(strict_types=1);

namespace App\DataTransferObjects\Command;

class CommandUpdateDto
{
    private string $title;

    /** @var mixed[] */
    private array $rows;

    /**
     * @param mixed[] $rows
     */
    public function __construct(string $title, array $rows)
    {
        $this->title = $title;
        $this->rows = $rows;
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
}
