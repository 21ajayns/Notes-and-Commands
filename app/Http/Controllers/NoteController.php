<?php

namespace App\Http\Controllers;

use App\Dto\NoteDto;
use App\Http\Requests\NoteRequest;
use App\Repositories\Contracts\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    private NoteRepositoryInterface $repo;

    public function __construct(NoteRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(NoteRequest $request): JsonResponse
    {
        $note = $this->repo->create(new NoteDto(
            $request->get('title'),
            $request->get('body'),
            $request->get('category')
        ));

        return new JsonResponse($note->toArray(), Response::HTTP_CREATED);
    }

    public function getAll(): JsonResponse
    {
        $notes = $this->repo->getAll();

        return new JsonResponse(['notes' => $notes->toArray()]);
    }
}
