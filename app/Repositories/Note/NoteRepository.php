<?php
declare(strict_types=1);

namespace App\Repositories\Note;

use App\DataTransferObjects\Note\NoteCreateDto;
use App\DataTransferObjects\Note\NoteUpdateDto;
use App\Models\Note\Note;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(NoteCreateDto $createDto): Note
    {
        $note = new Note();
        $note->setAttribute('title', $createDto->getTitle());
        $note->setAttribute('content', $createDto->getContent());
        $note->setAttribute('category', $createDto->getCategory()->getValue());
        $note->setAttribute('folder_id', $createDto->getFolderId());

        $note->save();

        return $note;
    }

    public function all(?string $folderId = null, ?string $category = null): Collection
    {
        return Note::query()
            ->where('folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function find(string $id): Note
    {
        return Note::query()->findOrFail($id);
    }

    public function update(string $id, NoteUpdateDto $updateDto): Note
    {
        $note = Note::query()->findOrFail($id);

        $note->setAttribute('title', $updateDto->getTitle());
        $note->setAttribute('content', $updateDto->getContent());

        $note->save();

        return $note;
    }

    public function delete(string $id): void
    {
        Note::query()->findOrFail($id)->delete();
    }
}
