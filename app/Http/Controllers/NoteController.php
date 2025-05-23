<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created note here
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'noteable_id' => 'required|integer',
            'noteable_type' => 'required|string',
        ]);

        // Ensure the noteable_type is a valid model
        $validModels = ['App\\Models\\Company', 'App\\Models\\Employee'];
        if (!in_array($request->noteable_type, $validModels) || !class_exists($request->noteable_type)) {
            return back()->withErrors(['noteable_type' => 'Invalid noteable type provided.']);
        }

        $model = $request->noteable_type::find($request->noteable_id);

        if (!$model) {
            return back()->withErrors(['noteable_id' => 'Associated record not found.']);
        }

        $note = $model->notes()->create([
            'body' => $request->body,
            'user_id' => Auth::id(), 
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    
    public function destroy(Note $note)
    {
        
        if (Auth::id() !== $note->user_id && !Auth::user()->hasRole('admin')) { 
            abort(403, 'Unauthorized action.');
        }

        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}
