<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\CommandFolder;

use App\DataTransferObjects\CommandFolder\CommandFolderCreateDto;
use App\DataTransferObjects\CommandFolder\CommandFolderUpdateDto;
use App\Models\Command\CommandFolder;
use Illuminate\Database\Eloquent\Collection;

interface CommandFolderRepositoryInterface
{
    public function create(CommandFolderCreateDto $createDto): CommandFolder;

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection;

    public function update(string $organizationId, string $id, CommandFolderUpdateDto $updateDto): CommandFolder;

    public function delete(string $organizationId, string $id): void;
}
