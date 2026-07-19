<?php

namespace App\Http\Controllers;

use App\Dto\FolderDto;
use App\Http\Requests\FolderRequest;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class FolderController extends Controller
{
    private FolderRepositoryInterface $repo;

    public function __construct(FolderRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(FolderRequest $request): JsonResponse|RedirectResponse
    {
        $folder = $this->repo->create(
            new FolderDto(
                $request->get('name'),
                $request->get('parent_id')
            ),
            $request->user()
        );

        if ($request->expectsJson()) {
            return new JsonResponse($folder->toArray(), Response::HTTP_CREATED);
        }

        return redirect()->back()->with('status', 'Folder created.');
    }
}
