<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class StreamPlaylistJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $command)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            exec($this->command);
        } catch (Throwable $e) {
            info('Error streaming playlist: ' . $e->getMessage());
        }
    }
}
