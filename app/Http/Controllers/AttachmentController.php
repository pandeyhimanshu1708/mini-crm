<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly uploaded attachment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'attachable_id' => 'required|integer',
            'attachable_type' => 'required|string',
        ]);

        // Ensure the attachable_type is a valid model
        $validModels = ['App\\Models\\Company', 'App\\Models\\Employee'];
        if (!in_array($request->attachable_type, $validModels) || !class_exists($request->attachable_type)) {
            return back()->withErrors(['attachable_type' => 'Invalid attachable type provided.']);
        }

        $model = $request->attachable_type::find($request->attachable_id);

        if (!$model) {
            return back()->withErrors(['attachable_id' => 'Associated record not found.']);
        }

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/attachments'); // Store in storage/app/public/attachments

            $attachment = $model->attachments()->create([
                'filename' => $uploadedFile->getClientOriginalName(),
                'path' => str_replace('public/', '', $path), // Store path relative to storage/app/public
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('success', 'File attached successfully.');
        }

        return back()->with('error', 'No file uploaded.');
    }

    /**
     * Download the specified attachment.
     */
    public function download(Attachment $attachment)
    {
       

        if (Storage::disk('public')->exists($attachment->path)) {
            return Storage::disk('public')->download($attachment->path, $attachment->filename);
        }

        return back()->with('error', 'File not found.');
    }

   
    public function destroy(Attachment $attachment)
    {
        
        if (Auth::id() !== $attachment->user_id && !Auth::user()->hasRole('admin')) { 
            abort(403, 'Unauthorized action.');
        }

        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
