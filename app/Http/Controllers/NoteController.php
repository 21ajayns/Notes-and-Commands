<?php

namespace App\Http\Controllers;

use App\Dto\NoteDto;
use App\Http\Requests\NoteRequest;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    private NoteRepositoryInterface $repo;

    private FolderRepositoryInterface $folderRepo;

    public function __construct(NoteRepositoryInterface $repo, FolderRepositoryInterface $folderRepo)
    {
        $this->repo = $repo;
        $this->folderRepo = $folderRepo;
    }

    public function create(NoteRequest $request): JsonResponse|RedirectResponse
    {
        $note = $this->repo->create(new NoteDto(
            $request->get('title'),
            $request->get('body'),
            $request->get('folder_id') !== null ? (int) $request->get('folder_id') : null
        ));

        if ($request->expectsJson()) {
            return new JsonResponse($note->toArray(), Response::HTTP_CREATED);
        }

        return redirect()->route('notes.index')->with('status', 'Note created.');
    }

    public function getAll(Request $request): JsonResponse|View
    {
        $notes = $this->repo->getAll();

        if ($request->expectsJson()) {
            return new JsonResponse(['notes' => $notes->toArray()]);
        }

        return view('dashboard', [
            'notes' => $notes,
            'folders' => $this->folderRepo->getAll(),
        ]);
    }
}
