<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamController extends Controller
{
    public function admin()
    {
        $audioFiles = Storage::files('public/audio');
        $audioFiles = collect($audioFiles)->map(function ($file) {
            return basename($file);
        });

        return view('stream', compact('audioFiles'));
    }

    public function listener()
    {
        return view('listener');
    }

    public function stream($filename)
    {
        $path = storage_path('app/public/audio/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }
        
        $stream = new StreamedResponse(function () use ($path) {
            $stream = fopen($path, 'r');
            $buffer = 8192;

            while (!feof($stream)) {
                echo fread($stream, $buffer);
                flush();
            }

            fclose($stream);
        });

        $stream->headers->set('Content-Type', 'audio/mpeg');
        $stream->headers->set('X-Accel-Buffering', 'no');
        $stream->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $stream;
    }

    public function broadcastState(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|string',
            'filename' => 'nullable|string',
            'currentTime' => 'nullable|numeric'
        ]);

        event(new \App\Events\StreamStateChanged($data));

        return response()->json(['status' => 'success']);
    }

}
