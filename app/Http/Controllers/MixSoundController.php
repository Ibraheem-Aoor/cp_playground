<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Http;

class MixSoundController extends Controller
{
    public function mixAudio(
        array $inputUrls = [
            'https://cdn.freesound.org/previews/762/762061_5828667-lq.mp3',
            'https://cdn.freesound.org/previews/763/763172_12880153-lq.mp3',
        ]
    ) {
        $outputFilePath = storage_path("app/public/output_mix.mp3");

        // Step 1: Download each file locally
        $localFiles = [];
        foreach ($inputUrls as $index => $url) {
            $filePath = storage_path("app/public/temp_audio_{$index}.mp3");
            file_put_contents($filePath, file_get_contents($url));
            $localFiles[] = $filePath;
        }

        // Step 2: Build the FFmpeg command with amix filter
        $ffmpegPath = 'C:/ffmpeg/bin/ffmpeg.exe';  // Make sure this path is correct
        $inputFiles = '';
        foreach ($localFiles as $file) {
            $inputFiles .= " -i " . escapeshellarg($file);
        }

        // Define the amix filter based on the number of inputs
        $amixFilter = "amix=inputs=" . count($localFiles) . ":duration=longest";

        // Final command to mix the audio
        $command = "$ffmpegPath $inputFiles -filter_complex \"$amixFilter\" -y " . escapeshellarg($outputFilePath);

        // Execute the command
        exec($command, $output, $returnVar);

        // Step 3: Cleanup temporary files
        foreach ($localFiles as $file) {
            @unlink($file);
        }

        // Check for command success
        if ($returnVar !== 0) {
            return response()->json(['error' => 'Failed to mix audio'], 500);
        }

        // Return the mixed audio file as a download
        return response()->download($outputFilePath)->deleteFileAfterSend(true);
    }
}
