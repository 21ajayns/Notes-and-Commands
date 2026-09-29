<?php
declare(strict_types=1);

namespace App\Repositories\CommandFolder;

use App\DataTransferObjects\CommandFolder\CommandFolderCreateDto;
use App\DataTransferObjects\CommandFolder\CommandFolderUpdateDto;
use App\Models\Command\CommandFolder;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CommandFolderRepository implements CommandFolderRepositoryInterface
{
    public function create(CommandFolderCreateDto $createDto): CommandFolder
    {
        $folder = new CommandFolder();
        $folder->setAttribute('organization_id', $createDto->getOrganizationId());
        $folder->setAttribute('name', $createDto->getName());
        $folder->setAttribute('category', $createDto->getCategory()->getValue());
        $folder->setAttribute('command_folder_id', $createDto->getFolderId());

        $folder->save();

        return $folder;
    }

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection
    {
        return $this->forOrganization($organizationId)
            ->where('command_folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function update(string $organizationId, string $id, CommandFolderUpdateDto $updateDto): CommandFolder
    {
        $folder = $this->forOrganization($organizationId)->findOrFail($id);

        $folder->setAttribute('name', $updateDto->getName());

        $folder->save();

        return $folder;
    }

    public function delete(string $organizationId, string $id): void
    {
        $this->forOrganization($organizationId)->findOrFail($id)->delete();
    }

    private function forOrganization(string $organizationId): Builder
    {
        return CommandFolder::query()->where('organization_id', $organizationId);
    }
}
