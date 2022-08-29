<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::with('user', 'category')->get();

        return ResponseFormatter::success($notes, 'notes retrieved');
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        //get one user first
        $note = Note::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => User::first()->id,
        ]);

        if ($request->tags) {
            $note->tags()->attach($request->tags);
        }
        return ResponseFormatter::success($note, 'note created');
    }

    public function show($id)
    {
        $note = Note::find($id);

        if ($note) {
            return ResponseFormatter::success($note, 'Note found');
        }
        return ResponseFormatter::error('Note not found', null, 404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        $note = Note::find($id);
        if ($note) {
            $note->title = $request->title ?? $note->title;
            $note->body = $request->body ?? $note->body;
            $note->category_id = $request->category_id ?? $note->category_id;
            $note->save();
            if ($request->tags) {
                $note->tags()->sync($request->tags);
            }

            return ResponseFormatter::success($note, 'note updated');
        }
        return ResponseFormatter::error('Note not found', null, 404);

    }

    public function delete($id)
    {
        $note = Note::find($id);
        if ($note) {
            $note->delete();
            return ResponseFormatter::success($note, 'note deleted');
        }
        return ResponseFormatter::error('Note not found', null, 404);
    }
}
