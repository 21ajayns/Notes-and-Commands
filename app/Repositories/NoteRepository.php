<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\NoteDto;
use App\Models\Note;
use App\Repositories\Contracts\NoteRepositoryInterface;
use Illuminate\Support\Collection;

class NoteRepository implements NoteRepositoryInterface
{
    public function create(NoteDto $create): Note
    {
        $note = new Note();

        $note->setAttribute('title', $create->getTitle());
        $note->setAttribute('body', $create->getBody());
        $note->setAttribute('category', $create->getCategory());

        $note->save();

        return $note;
    }

    public function getAll(): Collection
    {
        return (new Note())->all();
    }
}
