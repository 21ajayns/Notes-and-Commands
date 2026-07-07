<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return view('dashboard', compact('notes'));
    }

    public function store(Request $request)
    {
        Note::create([
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
        ]);

        return redirect()->back();
    }
}
