<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Note;

use App\DataTransferObjects\Note\NoteCreateDto;
use App\Models\Note\Note;

interface NoteRepositoryInterface
{
    public function create(NoteCreateDto $createDto): Note;
}
