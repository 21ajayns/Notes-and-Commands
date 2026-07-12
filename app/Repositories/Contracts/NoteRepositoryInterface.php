<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Dto\NoteDto;
use App\Models\Note;
use Illuminate\Support\Collection;

interface NoteRepositoryInterface
{
    public function create(NoteDto $create): Note;

    public function getAll(): Collection;
}
