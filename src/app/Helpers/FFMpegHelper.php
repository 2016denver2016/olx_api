<?php

namespace App\Helpers;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

class FFMpegHelper
{
    private static $instance;
    private $ffmpeg;
    private $ffprobe;

    private function __construct()
    {
        try {
            $this->ffmpeg  = FFMpeg::create();
            $this->ffprobe = FFProbe::create();
        } catch (\Exception $e) {
            $config = [
                'ffmpeg.binaries'  => env('FFMPEG_BIN_PATH'),
                'ffprobe.binaries' => env('FFPROBE_BIN_PATH'),
                'timeout'          => 3600, // The timeout for the underlying process
                'ffmpeg.threads'   => env("FFMPEG_THREADS"),   // The number of threads that FFMpeg should use
            ];
            $this->ffmpeg  = FFMpeg::create($config);
            $this->ffprobe = FFProbe::create($config);
        }
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getDuration(string $file)
    {
        return $this->ffprobe->format($file)->get('duration');
    }


}
