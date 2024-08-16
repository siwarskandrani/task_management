<?php

namespace App\Http\Controllers;

use App\Models\Media; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('task_media'); // on stolke les files dans le dossier storage/app/media

            $media = Media::create(['path' => $path]);

            return response()->json(['success' => 'Media uploaded successfully', 'media' => $media]);
        }

        return response()->json(['error' => 'No media file uploaded'], 400);
    }


    public function destroy($mediaId)
    {
        $media = Media::find($mediaId);
        if ($media) {
            // Delete the file from storage
            \Storage::disk('public')->delete($media->path);
            
            // Delete the record from the database
            $media->delete();
            
            return redirect()->back()->with('success', 'Media deleted successfully.');
        }

        return redirect()->back()->with('error', 'Media not found.');
    }
}
