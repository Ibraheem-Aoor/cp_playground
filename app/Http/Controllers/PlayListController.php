<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\scheduledPlayback;
use App\Models\Song;
use Illuminate\Http\Request;

class PlayListController extends Controller
{
    public function userForm()
    {
        $data['playlists'] = Playlist::query()->get();
        return view('playlist' , $data);
    }
    public function storePlaylist(Request $request)
    {
        $playlist = Playlist::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
        ]);

        foreach ($request->input('songs') as $index => $songId) {
            $playlist->songs()->attach($songId, ['order' => $index]);
        }

        return redirect()->route('user.playlists.index')->with('success', 'Playlist created!');
    }

    public function schedulePlayback(Request $request)
    {
        // Validate form input
        $request->validate([
            'playlist_id' => 'required|exists:playlists,id',
            'frequency' => 'required|in:daily,weekly',
            'play_time' => 'required|date_format:H:i',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        // Create the scheduled playback
        $scheduledPlayback = scheduledPlayback::create([
            'playlist_id' => $request->input('playlist_id'),
            'frequency' => $request->input('frequency'),
            'play_time' => $request->input('play_time'),
            'days_of_week' => $request->input('frequency') === 'weekly'
                ? json_encode($request->input('days_of_week'))
                : null,
        ]);

        return redirect()->back()->with('success', 'Playback scheduled successfully!');
    }
}
