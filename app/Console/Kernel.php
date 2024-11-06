<?php

namespace App\Console;

use App\Events\NewTrackBroadcasted;
use App\Models\scheduledPlayback;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $now = now();
            $scheduledPlaybacks = ScheduledPlayback::get();
            info($scheduledPlaybacks);

            foreach ($scheduledPlaybacks as $playback) {
                // Assuming each playlist has a 'tracks' relationship with track URLs and titles
                info($playback->playlist->songs()->pluck('url')->toArray());
                event(new NewTrackBroadcasted($playback->playlist->songs()->pluck('url')->toArray()));
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
