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
            $path = $file->store('media', 'public');

            $media = Media::create(['path' => $path]);

            return response()->json(['success' => 'Media uploaded successfully', 'media' => $media]);
        }

        return response()->json(['error' => 'No media file uploaded'], 400);
    }
}
