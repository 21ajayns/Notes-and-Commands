<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteGetController extends Controller
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

        $folderId = $request->get('folder_id') ? (int) $request->get('folder_id') : null;

        $allFolders = $this->folderRepo->getAll($user);

        $folders = $allFolders->where('parent_id', $folderId)->values();
        $notes = $this->noteRepo->getAll($user)->where('parent_id', $folderId)->values();

        if ($request->expectsJson()) {
            return new JsonResponse([
                'folders' => $folders,
                'notes' => $notes,
            ]);
        }

        return view('content', [
            'folders' => $folders,
            'notes' => $notes,
            'allFolders' => $allFolders,
            'currentFolder' => $folderId ? $allFolders->firstWhere('id', $folderId) : null,
        ]);
    }
}
