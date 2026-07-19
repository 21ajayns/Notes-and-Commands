<?php

namespace App\Http\Controllers;

use App\Dto\NoteDto;
use App\Http\Requests\NoteRequest;
use App\Models\Folder;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    private NoteRepositoryInterface $repo;

    public function __construct(NoteRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(NoteRequest $request): JsonResponse|RedirectResponse
    {
        $parent = $request->get('parent_id') ? Folder::find($request->get('parent_id')) : null;

        $note = $this->repo->create(
            new NoteDto(
                $request->get('title'),
                $request->get('body')
            ),
            $request->user(),
            $parent
        );

        if ($request->expectsJson()) {
            return new JsonResponse($note->toArray(), Response::HTTP_CREATED);
        }

        return redirect()->back()->with('status', 'Note created.');
    }
}
