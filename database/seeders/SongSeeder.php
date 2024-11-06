<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data as $song) {
            Song::create($song);
        }
    }

    public function getData()
    {
        return [
            [
                'title' => 'The Greatest Song Ever',
                'artist' => 'The Greatest Artist Ever',
                'url' => 'https://cdn.freesound.org/previews/762/762061_5828667-lq.mp3',
                'playlist_id' => 1,
            ],
            [
                'title' => 'The Second Greatest Song Ever',
                'artist' => 'The Second Greatest Artist Ever',
                'url' => 'https://cdn.freesound.org/previews/762/762061_5828667-lq.mp3',
                'playlist_id' => 1,
            ],
        ];
    }
}
