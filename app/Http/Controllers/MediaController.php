<?php

namespace App\Http\Controllers;

use App\Models\Media; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;

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


    public function destroy($taskId, $mediaId)
    {
        // Valider l'existence de la tâche et du média, puis procéder à la suppression
        $task = Task::findOrFail($taskId);
        $media = $task->media()->findOrFail($mediaId);
        Storage::delete($media->path); // Supprimer le fichier du stockage

        $media->delete(); // Supprimer l'enregistrement du média

        return redirect()->back()->with('success', 'Média supprimé avec succès.');
    }
}
    
