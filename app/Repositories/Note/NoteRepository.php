<?php
declare(strict_types=1);

namespace App\Repositories\Note;

use App\DataTransferObjects\Note\NoteCreateDto;
use App\DataTransferObjects\Note\NoteUpdateDto;
use App\Models\Note\Note;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(NoteCreateDto $createDto): Note
    {
        $note = new Note();
        $note->setAttribute('organization_id', $createDto->getOrganizationId());
        $note->setAttribute('title', $createDto->getTitle());
        $note->setAttribute('content', $createDto->getContent());
        $note->setAttribute('category', $createDto->getCategory()->getValue());
        $note->setAttribute('folder_id', $createDto->getFolderId());

        $note->save();

        return $note;
    }

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection
    {
        return $this->forOrganization($organizationId)
            ->where('folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function find(string $organizationId, string $id): Note
    {
        return $this->forOrganization($organizationId)->findOrFail($id);
    }

    public function update(string $organizationId, string $id, NoteUpdateDto $updateDto): Note
    {
        $note = $this->forOrganization($organizationId)->findOrFail($id);

        $note->setAttribute('title', $updateDto->getTitle());
        $note->setAttribute('content', $updateDto->getContent());

        $note->save();

        return $note;
    }

    public function delete(string $organizationId, string $id): void
    {
        $this->forOrganization($organizationId)->findOrFail($id)->delete();
    }

    private function forOrganization(string $organizationId): Builder
    {
        return Note::query()->where('organization_id', $organizationId);
    }
}
