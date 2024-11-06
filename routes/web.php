<?php

use App\Events\NewTrackBroadcasted;
use App\Http\Controllers\M3u8Controller;
use App\Http\Controllers\MixSoundController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PlayListController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

#https://cdn.freesound.org/displays/763/763131_5674468_wave_bw_M.png

#https://cdn.freesound.org/previews/762/762061_5828667-lq.mp3
#https://cdn.freesound.org/previews/763/763172_12880153-lq.mp3
Route::get('/broadcast-track', function () {
    // Example track URL and title
    $trackUrl = 'https://cdn.freesound.org/previews/763/763172_12880153-lq.mp3';
    $trackTitle = 'New Live Track';

    // Broadcast the event with track data
    // event(new NewTrackBroadcasted($trackUrl, $trackTitle));

    return response()->json(['status' => 'Track broadcasted successfully']);
});

// Route::get('mix-sounds' , [MixSoundController::class, 'mixAudio']);
// Route::get('player' , function(){
//     return view('player' , ['song' => 'https://cdn.freesound.org/previews/762/762061_5828667-lq.mp3']);
// });

Route::get('playlist', [PlayListController::class, 'userForm']);
Route::post('playlist/store', [PlayListController::class, 'storePlaylist'])->name('playlists.store');
Route::post('playlist/schedule', [PlayListController::class, 'schedulePlayback'])->name('playlists.schedule');


Route::get('/music/admin', [MusicController::class, 'adminView']);
Route::get('/music/client', [MusicController::class, 'clientView']);

Route::get('/stream-admin', [StreamController::class, 'admin']);
Route::get('/stream-listener', [StreamController::class, 'listener']);
Route::post('/broadcast-stream-state', [StreamController::class, 'broadcastState']);

Route::get('/stream/{filename}', [StreamController::class, 'stream']);



// MU38

Route::get('/add-song', [M3u8Controller::class, 'showForm'])->name('song.form');
Route::post('/save-song', [M3u8Controller::class, 'saveSong'])->name('song.save');
Route::get('stream-song', [M3u8Controller::class, 'streamSongToDisk'])->name('stream.song');
Route::get('view-stream-song', [M3u8Controller::class, 'showStreamedSong'])->name('view.stream.song');
