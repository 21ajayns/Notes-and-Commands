<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\NoteDto;
use App\Models\Note;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Support\Collection;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(NoteDto $create): Note
    {
        $note = new Note();

        $note->setAttribute('title', $create->getTitle());
        $note->setAttribute('body', $create->getBody());
        $note->setAttribute('folder_id', $create->getFolderId());

        $note->save();

        return $note;
    }

    public function getAll(): Collection
    {
        return Note::with('folder')->get();
    }
}
