<!DOCTYPE html>
<html>
<head>
    <title>Music Admin</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.9.0/dist/echo.iife.min.js"></script>
</head>
<body>
    <div id="audio-player">
        <audio id="player" src="{{ $tracks->first()->url }}" controls></audio>
        <button onclick="controlMusic('play')">Play</button>
        <button onclick="controlMusic('pause')">Pause</button>
        <select id="track-select" onchange="changeTrack(this.value)">
            @foreach($tracks as $track)
                <option value="{{ $track->id }}">{{ $track->title }}</option>
            @endforeach
        </select>
    </div>

    <script>
        // Initialize Echo with private channel
        const echo = new Echo({
            broadcaster: 'pusher',
            key: "{{ env('PUSHER_APP_KEY') }}",
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        const player = document.getElementById('player');

        // Listen for seeking events
        player.addEventListener('seeked', function() {
            broadcastState('seek', player.currentTime);
        });

        // Listen for play/pause events
        player.addEventListener('play', function() {
            broadcastState('play', player.currentTime);
        });

        player.addEventListener('pause', function() {
            broadcastState('pause', player.currentTime);
        });

        function broadcastState(action, currentTime) {
            echo.channel('music-sync').whisper('musicStateChanged', {
                action: action,
                trackId: document.getElementById('track-select').value,
                currentTime: currentTime
            });
        }

        function controlMusic(action) {
            if (action === 'play') {
                player.play();
            } else if (action === 'pause') {
                player.pause();
            }
        }

        function changeTrack(trackId) {
            // Update audio source
            player.src = `/tracks/${trackId}`;

            // Broadcast track change
            echo.private('music-sync').whisper('musicStateChanged', {
                action: 'trackChanged',
                trackId: trackId,
                currentTime: 0
            });
        }
    </script>
</body>
</html>
