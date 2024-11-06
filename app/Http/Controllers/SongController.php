<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SongController extends Controller
{
    public function create()
    {
        return view('admin.songs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'artist' => 'required|string',
            'url' => 'required|url',
        ]);

        Song::create($request->only(['title', 'artist', 'url']));
        return redirect()->route('admin.songs.index')->with('success', 'Song added successfully!');
    }
}
