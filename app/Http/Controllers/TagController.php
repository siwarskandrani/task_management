<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->input('name'),
         //   'user_id' => auth()->id(),
        ]);

        return response()->json(['tag' => $tag]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $tags = Tag::where('name', 'like', "%{$query}%")->get();
        return response()->json($tags);
    }



}

