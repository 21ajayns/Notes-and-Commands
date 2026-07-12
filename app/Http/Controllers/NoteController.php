<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Repositories\Contracts\NoteRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $notes
    ) {
    }

    public function index(): View
    {
        return view('dashboard', [
            'notes' => $this->notes->all(),
        ]);
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $this->notes->create($request->validated());

        return redirect()
            ->route('notes.index')
            ->with('status', 'Note created.');
    }
}
