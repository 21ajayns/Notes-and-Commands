<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Note;

use App\DataTransferObjects\Note\NoteCreateDto;
use App\DataTransferObjects\Note\NoteUpdateDto;
use App\Models\Note\Note;
use Illuminate\Database\Eloquent\Collection;

interface NoteRepositoryInterface
{
    public function create(NoteCreateDto $createDto): Note;

    public function all(?string $folderId = null, ?string $category = null): Collection;

    public function find(string $id): Note;

    public function update(string $id, NoteUpdateDto $updateDto): Note;
}
