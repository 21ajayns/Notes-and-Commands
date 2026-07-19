<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Dto\NoteDto;
use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Collection;

interface NoteRepositoryInterface
{
    public function create(NoteDto $create, User $user, ?Folder $parent = null): Note;

    public function getAll(User $user): Collection;
}
