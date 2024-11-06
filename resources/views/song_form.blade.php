<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song to Playlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Song to Playlist</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('song.save') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="song_url" class="form-label">Song URL</label>
                <input type="url" name="song_url" id="song_url" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (Seconds)</label>
                <input type="number" name="duration" id="duration" class="form-control" min="1">
            </div>
            <button type="submit" class="btn btn-primary">Add Song</button>
        </form>
    </div>
</body>
</html>
