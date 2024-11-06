<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;
class M3U8Generator
{
    protected $playlistItems = [];
    protected $basePath = "playlists"; // Folder to store .m3u8 files
    /**
     * Add a song to the playlist.
     *
     * @param string $songUrl URL or path of the song
     * @param int|null $duration Duration in seconds, if known
     */
    public function addSong(string $songUrl, int $duration = null)
    {
        $this->playlistItems[] = [
            'type' => 'song',
            'url' => $songUrl,
            'duration' => $duration
        ];
    }
    /**
     * Add a radio stream to the playlist.
     *
     * @param string $streamUrl URL of the radio stream
     */
    public function addRadioStream(string $streamUrl)
    {
        $this->playlistItems[] = [
            'type' => 'radio',
            'url' => $streamUrl,
            'duration' => null // Radio streams have no set duration
        ];
    }
    /**
     * Add multiple songs from a playlist with specific frequency.
     *
     * @param array $songs Array of song URLs
     * @param int $frequency Frequency of playback in seconds
     */
    public function addPlaylist(array $songs, int $frequency)
    {
        foreach ($songs as $songUrl) {
            $this->playlistItems[] = [
                'type' => 'song',
                'url' => $songUrl,
                'duration' => $frequency
            ];
        }
    }
    /**
     * Generate the M3U8 playlist content.
     *
     * @return string
     */
    public function generateM3U8Content(): string
    {
        $content = "#EXTM3U\n"; // M3U8 file header
        foreach ($this->playlistItems as $item) {
            if ($item['type'] === 'song') {
                $duration = $item['duration'] ?? -1; // -1 if unknown
                $content .= "#EXTINF:$duration,\n";
                $content .= $item['url'] . "\n";
            } elseif ($item['type'] === 'radio') {
                $content .= "#EXTINF:-1,\n";
                $content .= $item['url'] . "\n";
            }
        }
        return $content;
    }
    /**
     * Save the M3U8 playlist to storage.
     *
     * @param string $fileName The name of the file
     * @return string Full path to the stored M3U8 file
     */
    public function saveM3U8File(string $fileName): string
    {
        $content = $this->generateM3U8Content();
        $filePath = "{$this->basePath}/{$fileName}.m3u8";
        // Save to storage (e.g., local, s3)
        Storage::disk('public')->put($filePath, $content);
        return Storage::url($filePath);
    }
    /**
     * Reset the playlist.
     */
    public function resetPlaylist()
    {
        $this->playlistItems = [];
    }
}
