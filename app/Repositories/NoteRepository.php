<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\NoteDto;
use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Support\Collection;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(NoteDto $create, User $user, ?Folder $parent = null): Note
    {
        $note = new Note();

        $note->setAttribute('title', $create->getTitle());
        $note->setAttribute('body', $create->getBody());

        $note->user()->associate($user);

        if ($parent) {
            $note->parent()->associate($parent);
        }

        $note->save();

        return $note;
    }

    public function getAll(): Collection
    {
        return Note::with(['user', 'parent'])->get();
    }
}
