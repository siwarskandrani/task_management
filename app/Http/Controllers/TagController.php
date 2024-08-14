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
            'user_id' => auth()->id(), // Optional, if you want to link tags to users
        ]);

        return response()->json(['tag' => $tag]);
    }
}

