<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User - Stream Song</title>
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
</head>
<body>
    <h1>Listen to the Song</h1>

    @if($song)
        <audio id="audio-player" class="video-js vjs-default-skin" controls preload="auto" width="600" data-setup="{}">
            <source src=" {{  $song}}" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
    @else
        <p>No song available to stream.</p>
    @endif

    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>
    <script>
        var player = videojs('audio-player');
    </script>
</body>
</html>
