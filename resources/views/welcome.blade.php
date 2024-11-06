<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts and MediaElement.js -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mediaelement@5.0.4/build/mediaelementplayer.min.css">
    <script src="https://cdn.jsdelivr.net/npm/mediaelement@5.0.4/build/mediaelement-and-player.min.js"></script>
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />

</head>

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gray-100 dark:bg-gray-900">
                <div class="max-w-7xl mx-auto p-6 lg:p-8">
                    <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                        <div id="nowPlaying">Now playing: Loading...</div>
                        <video id="audioPlayer" class="video-js" controls preload="none" width="640" height="264">
                            <source src="" type="audio/mp3">
                            Your browser does not support the audio element.
                        </video>
                        <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </div>
                        <button id="playButton" hidden>Start Playback</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.9.0/dist/echo.iife.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const trackTitleElement = document.getElementById("nowPlaying");
            const audioElement = videojs('audioPlayer'); // Initialize Video.js

            let currentTrackIndex = 0; // Keep track of the current track index
            let tracks = []; // Array to hold the tracks

            const echo = new Echo({
                broadcaster: 'pusher',
                key: "{{ env('PUSHER_APP_KEY') }}",
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                forceTLS: true
            });

            echo.channel('live-stream').listen('NewTracksBroadcasted', (data) => {
                tracks = data.tracks; // Store received tracks
                currentTrackIndex = 0; // Reset to the first track
                playNextTrack(); // Start playback
            });

            // Function to play the next track
            function playNextTrack() {
                if (currentTrackIndex < tracks.length) {
                    const track = tracks[currentTrackIndex];
                    trackTitleElement.innerText = `Now playing: ${track.title}`;
                    audioElement.src({ type: 'audio/mp3', src: track.url });
                    audioElement.play().then(() => {
                        console.log(`Playing: ${track.title}`);
                    }).catch((error) => {
                        console.error("Playback error:", error);
                    });

                    // Increment the index for the next track
                    currentTrackIndex++;

                    // Listen for when the current track ends to play the next one
                    audioElement.on('ended', playNextTrack);
                } else {
                    console.log("All tracks have been played.");
                    // Optionally reset or handle the end of playback
                    currentTrackIndex = 0; // Reset for future playback if needed
                }
            }
        });
    </script>
    </body>

</html>
