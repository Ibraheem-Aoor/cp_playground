<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stream Playlist</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Stream Your Playlist</h2>

        <button id="playButton">Play Song</button>

        <!-- Audio Element -->
        <audio id="audio" controls>
            Your browser does not support the audio tag.
        </audio>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        var audio = document.getElementById('audio');
        var playButton = document.getElementById('playButton');
        var audioSrc = '{{ $file_url }}'; // Pass your M3U8 URL here

        // Log the URL to check if it's correct
        console.log("M3U8 File URL: ", audioSrc);

        // Check if HLS.js is supported
        if (Hls.isSupported()) {
            var hls = new Hls();

            // Bind event listeners for HLS.js
            hls.on(Hls.Events.ERROR, function (event, data) {
                console.error("HLS.js Error", data);
            });

            hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
                console.log('Manifest loaded', data);
            });

            hls.on(Hls.Events.LEVEL_SWITCHED, function (event, data) {
                console.log('Switched to level', data.level);
            });

            hls.loadSource(audioSrc);
            hls.attachMedia(audio);

            // When the manifest is parsed, try to play
            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                console.log('Manifest parsed successfully');
                playButton.addEventListener('click', function () {
                    audio.play().catch(function (error) {
                        console.error("Error attempting to play the audio:", error);
                    });
                });
            });
        } else if (audio.canPlayType('application/vnd.apple.mpegurl')) {
            // Safari can handle HLS natively
            audio.src = audioSrc;

            // Add click listener to play the audio
            playButton.addEventListener('click', function () {
                audio.play().catch(function (error) {
                    console.error("Error attempting to play the audio:", error);
                });
            });
        } else {
            alert('Your browser does not support HLS streaming.');
        }
    </script>
</body>

</html>
