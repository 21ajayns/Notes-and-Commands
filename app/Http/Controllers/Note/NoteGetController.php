<?php
declare(strict_types=1);

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\NoteGetRequest;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NoteGetController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository
    ) {
    }

    public function __invoke(NoteGetRequest $request): JsonResponse
    {
        $notes = $this->noteRepository->all(
            $request->validated('folder_id'),
            $request->validated('category')
        );

        return response()->json($notes);
    }
}
