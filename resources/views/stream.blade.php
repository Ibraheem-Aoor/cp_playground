// resources/views/stream/admin.blade.php
<!DOCTYPE html>
<html>

<head>
    <title>Stream Admin</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <style>
        .admin-panel {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stream-controls {
            margin: 20px 0;
        }

        .status-indicator {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .streaming {
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
    <div class="admin-panel">
        <h2>Stream Admin Panel</h2>

        <div class="stream-controls">
            <select id="audioSelect">
                @foreach ($audioFiles as $file)
                    <option value="{{ $file }}">{{ $file }}</option>
                @endforeach
            </select>

            <audio id="audioPlayer" controls>
                Your browser does not support the audio element.
            </audio>
        </div>

        <div id="streamStatus" class="status-indicator offline">
            Stream is offline
        </div>

        <button onclick="startStreaming()">Start Streaming</button>
        <button onclick="stopStreaming()">Stop Streaming</button>
    </div>

    // Update admin.blade.php script section:
    <script>
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        const channel = pusher.subscribe('audio-stream');
        const audioPlayer = document.getElementById('audioPlayer');
        const statusDiv = document.getElementById('streamStatus');
        const audioSelect = document.getElementById('audioSelect');

        function broadcastState(action, filename = null, currentTime = 0) {
            fetch('/broadcast-stream-state', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: action,
                    filename: filename,
                    currentTime: currentTime
                })
            });
        }

        function startStreaming() {
            const selectedFile = audioSelect.value;
            const streamUrl = `/stream/${selectedFile}`;

            audioPlayer.src = streamUrl;
            audioPlayer.play()
                .then(() => {
                    updateStatus(true);
                    broadcastState('start', selectedFile, audioPlayer.currentTime);
                })
                .catch(error => {
                    console.error('Streaming failed:', error);
                    updateStatus(false);
                });
        }

        function stopStreaming() {
            audioPlayer.pause();
            audioPlayer.src = '';
            updateStatus(false);
            broadcastState('stop');
        }

        function updateStatus(isStreaming) {
            statusDiv.textContent = isStreaming ? 'Stream is live' : 'Stream is offline';
            statusDiv.className = `status-indicator ${isStreaming ? 'streaming' : 'offline'}`;
        }

        // Sync current time periodically
        setInterval(() => {
            if (!audioPlayer.paused) {
                broadcastState('sync', audioSelect.value, audioPlayer.currentTime);
            }
        }, 5000);

        // Also listen for events from other potential admin tabs
        channel.bind('stream-state', function(data) {
            console.log('Stream state received:', data);
            handleStreamState(data);
        });
    </script>

    // Update listener.blade.php script section:
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
            switch (data.action) {
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
