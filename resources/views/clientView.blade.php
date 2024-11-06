<!DOCTYPE html>
<html>
<head>
    <title>Music Client</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.9.0/dist/echo.iife.min.js"></script>
</head>
<body>
    <div id="audio-player">
        <audio id="player" controls></audio>
        <div id="now-playing">Waiting for admin to start music...</div>
    </div>

    <script>
        const echo = new Echo({
            broadcaster: 'pusher',
            key: "{{ env('PUSHER_APP_KEY') }}",
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        const player = document.getElementById('player');

        // Disable controls for client
        player.controls = false;

        echo.channel('music-sync')
            .listenForWhisper('musicStateChanged', (e) => {
                console.log('Received event:', e);

                switch(e.action) {
                    case 'trackChanged':
                        player.src = `/tracks/${e.trackId}`;
                        player.currentTime = e.currentTime;
                        if (player.paused) player.play();
                        break;

                    case 'play':
                        player.currentTime = e.currentTime;
                        player.play();
                        break;

                    case 'pause':
                        player.pause();
                        break;

                    case 'seek':
                        player.currentTime = e.currentTime;
                        break;
                }
            });
    </script>
</body>
</html>
