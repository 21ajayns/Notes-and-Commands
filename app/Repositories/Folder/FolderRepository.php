<?php
declare(strict_types=1);

namespace App\Repositories\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Folder\FolderUpdateDto;
use App\Models\Folder\Folder;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(FolderCreateDto $createDto): Folder
    {
        $folder = new Folder();
        $folder->setAttribute('organization_id', $createDto->getOrganizationId());
        $folder->setAttribute('name', $createDto->getName());
        $folder->setAttribute('category', $createDto->getCategory()->getValue());
        $folder->setAttribute('folder_id', $createDto->getFolderId());

        $folder->save();

        return $folder;
    }

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection
    {
        return $this->forOrganization($organizationId)
            ->where('folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function update(string $organizationId, string $id, FolderUpdateDto $updateDto): Folder
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
        return Folder::query()->where('organization_id', $organizationId);
    }
}
