<?php
declare(strict_types=1);

namespace App\Repositories\Note;

use App\DataTransferObjects\Note\NoteCreateDto;
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

    public function all(?string $folderId = null): Collection
    {
        return Note::query()
            ->where('folder_id', $folderId)
            ->get();
    }
}
