<?php
declare(strict_types=1);

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NoteShowController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository
    ) {
    }

    public function __invoke(string $note): JsonResponse
    {
        return response()->json($this->noteRepository->find($note));
    }
}
