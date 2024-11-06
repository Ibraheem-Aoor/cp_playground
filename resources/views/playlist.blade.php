<!-- resources/views/schedule.blade.php -->

@extends('layouts.app')

@section('title', 'Schedule Playback')

@section('content')
<div class="card shadow-sm mt-5">
    <div class="card-header bg-primary text-white">
        <h1 class="h4 mb-0">Schedule Playback for Playlist</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('playlists.schedule') }}" method="POST">
            @csrf

            <!-- Playlist Selection -->
            <div class="mb-3">
                <label for="playlist_id" class="form-label">Playlist:</label>
                <select name="playlist_id" id="playlist_id" class="form-select" required>
                    <option selected disabled>Select a playlist</option>
                    @foreach($playlists as $playlist)
                        <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Frequency Selection -->
            <div class="mb-3">
                <label for="frequency" class="form-label">Frequency:</label>
                <select name="frequency" id="frequency" class="form-select" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>

            <!-- Play Time Selection -->
            <div class="mb-3">
                <label for="play_time" class="form-label">Time:</label>
                <input type="time" name="play_time" id="play_time" class="form-control" required>
            </div>

            <!-- Days of Week Selection for Weekly Frequency -->
            <div class="mb-3" id="daysOfWeekContainer" style="display: none;">
                <label for="days_of_week" class="form-label">Days (for Weekly):</label>
                <select name="days_of_week[]" id="days_of_week" class="form-select" multiple>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                <small class="form-text text-muted">Hold down Ctrl (Windows) or Command (Mac) to select multiple days.</small>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Set Schedule</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show or hide the days of the week selection based on frequency choice
    document.getElementById('frequency').addEventListener('change', function() {
        const daysOfWeekContainer = document.getElementById('daysOfWeekContainer');
        daysOfWeekContainer.style.display = this.value === 'weekly' ? 'block' : 'none';
    });
</script>
@endsection
