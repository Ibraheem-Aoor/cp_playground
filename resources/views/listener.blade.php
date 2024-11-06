<!DOCTYPE html>
<html>
<head>
    <title>Live Stream Listener</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <style>
        .listener-panel {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .live {
            background: #d4edda;
            color: #155724;
        }
        .offline {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="listener-panel">
        <h2>Live Stream</h2>

        <div id="statusMessage" class="status-message offline">
            Waiting for stream to start...
        </div>

        <audio id="audioPlayer" controls>
            Your browser does not support the audio element.
        </audio>
    </div>

    <script>
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        const channel = pusher.subscribe('audio-stream');
        const audioPlayer = document.getElementById('audioPlayer');
        const statusMessage = document.getElementById('statusMessage');

        channel.bind('stream-state', function(data) {
            console.log('Stream state received:', data);
            handleStreamState(data.data); // Note: data.data because of Laravel event structure
        });

        function handleStreamState(data) {
            switch(data.action) {
                case 'start':
                    audioPlayer.src = `/stream/${data.filename}`;
                    audioPlayer.currentTime = data.currentTime;
                    audioPlayer.play()
                        .then(() => updateStatus(true))
                        .catch(error => {
                            console.error('Playback failed:', error);
                            updateStatus(false);
                        });
                    break;

                case 'stop':
                    audioPlayer.pause();
                    audioPlayer.src = '';
                    updateStatus(false);
                    break;

                case 'sync':
                    // Only adjust time if the difference is significant
                    if (Math.abs(audioPlayer.currentTime - data.currentTime) > 1) {
                        audioPlayer.currentTime = data.currentTime;
                    }
                    break;
            }
        }

        function updateStatus(isLive) {
            statusMessage.textContent = isLive ? 'Stream is live' : 'Stream is offline';
            statusMessage.className = `status-message ${isLive ? 'live' : 'offline'}`;
        }

        // Handle stream errors
        audioPlayer.addEventListener('error', () => {
            updateStatus(false);
            console.error('Stream error occurred');
        });
    </script>
</body>
</html>
