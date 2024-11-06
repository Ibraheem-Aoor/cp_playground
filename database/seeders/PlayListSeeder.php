<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $playlists = [
            [
                'name' => fake()->name(),
            ],
            [
                'name' => fake()->name(),
            ],
            [
                'name' => fake()->name(),
            ],
        ];
        Playlist::insert($playlists);
    }
}
