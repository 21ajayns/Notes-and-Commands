<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    private FolderRepositoryInterface $folderRepo;

    private NoteRepositoryInterface $noteRepo;

    public function __construct(FolderRepositoryInterface $folderRepo, NoteRepositoryInterface $noteRepo)
    {
        $this->folderRepo = $folderRepo;
        $this->noteRepo = $noteRepo;
    }

    public function getAll(Request $request): JsonResponse|View
    {
        $user = $request->user();

        $folders = $this->folderRepo->getAll($user)->whereNull('parent_id')->values();
        $notes = $this->noteRepo->getAll($user)->whereNull('parent_id')->values();

        if ($request->expectsJson()) {
            return new JsonResponse([
                'folders' => $folders,
                'notes' => $notes,
            ]);
        }

        return view('content', [
            'folders' => $folders,
            'notes' => $notes,
            'allFolders' => $this->folderRepo->getAll($user),
        ]);
    }
}
