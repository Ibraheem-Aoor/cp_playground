<?php

namespace App\Http\Controllers;

use App\Jobs\StreamPlaylistJob;
use App\Services\M3U8Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class M3u8Controller extends Controller
{
    protected $m3u8Generator;

    public function __construct(M3U8Generator $m3u8Generator)
    {
        $this->m3u8Generator = $m3u8Generator;
    }

    /**
     * Show the form to add a song.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('song_form');
    }

    /**
     * Save the song URL and add it to the playlist.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSong(Request $request)
    {
        $validated = $request->validate([
            'song_url' => 'required|url',
            'duration' => 'nullable|integer|min:1',
        ]);

        $songUrl = $validated['song_url'];
        $duration = $validated['duration'] ?? null;

        // Add the song to the playlist using M3U8Generator
        $this->m3u8Generator->addSong($songUrl, $duration);

        // Save the M3U8 playlist to storage
        $fileUrl = $this->m3u8Generator->saveM3U8File('playlist'); // Save with the name 'playlist.m3u8'

        // Immediately stream the updated playlist
        $playlistUrl = $this->getPlayListUrl();  // Get the URL of the updated playlist
        $outputFile = storage_path('app/public/new.mp3'); // Generate a new output file name
        $ffmpegPath = 'C:/ffmpeg/bin/ffmpeg.exe';  // Path to FFMpeg

        // Build the FFMpeg command with progress output
        $command = "$ffmpegPath -i {$playlistUrl} -c copy {$outputFile}   -progress pipe:1";
        StreamPlaylistJob::dispatch($command);  // Dispatch the job to stream

        // Redirect back with a success message and the URL of the saved M3U8 file
        return redirect()->route('song.form')->with('success', 'Song added and streaming started! M3U8 playlist saved at: ' . $fileUrl);
    }


    public function getPlayListUrl()
    {
        return 'http://127.0.0.1:83/storage/playlists/playlist.m3u8';
    }
    public function showStreamedSong()
    {
        $data['file_url'] = $this->getPlayListUrl();
        return view('song_stream', $data);
    }

    public function streamSongToDisk()
    {
        $playlistUrl = $this->getPlayListUrl(); // Assuming this is your M3U8 file URL
        $outputFile = storage_path('app/public/test.mp3'); // Output file name
        $ffmpegPath = 'C:/ffmpeg/bin/ffmpeg.exe';  // Path to FFMpeg

        // Build the FFMpeg command with progress output
        $command = "$ffmpegPath -i {$playlistUrl} -c copy {$outputFile}  -progress pipe:1";
        StreamPlaylistJob::dispatch($command);

        // Return a success response with the progress
        return response()->json(['message' => 'File streamed and saved successfully', 'progress' => $command]);
    }




}
