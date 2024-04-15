<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Events\VideoEncodingStart;
use App\Events\VideoEncodingFinished;
use App\Events\VideoEncodingProgress;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;

class ConvertVideoFormat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new VideoEncodingStart());

        FFMpeg::fromDisk('public')
            ->open($this->video->video_path)
            ->export()
            ->toDisk('public')
            ->inFormat(new \FFMpeg\Format\Video\X264())
            ->onProgress(function ($percentage) {
                event(new VideoEncodingProgress($percentage));
            })
            ->afterSaving(function ($exporter, Media $media) {
                Storage::disk('public')->delete($this->video->video_path);

                $this->video->update([
                    'video_path' => $media->getPath()
                ]);

                event(new VideoEncodingFinished());
            })
            ->save('videos/' . Str::uuid() . '.mp4');
    }
}
