<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Command;

use App\DataTransferObjects\Command\CommandCreateDto;
use App\DataTransferObjects\Command\CommandUpdateDto;
use App\Models\Command\Command;
use Illuminate\Database\Eloquent\Collection;

interface CommandRepositoryInterface
{
    public function create(CommandCreateDto $createDto): Command;

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection;

    public function find(string $organizationId, string $id): Command;

    public function update(string $organizationId, string $id, CommandUpdateDto $updateDto): Command;

    public function delete(string $organizationId, string $id): void;
}
