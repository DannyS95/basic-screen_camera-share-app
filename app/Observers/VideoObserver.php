<?php

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Str;
use App\Jobs\ConvertVideoFormat;
use App\Jobs\GenerateVideoPreviewImage;
use Illuminate\Support\Facades\Storage;

class VideoObserver
{
    public function creating(Video $video)
    {
        $video->uuid = Str::uuid();
    }

    public function created(Video $video)
    {
        GenerateVideoPreviewImage::dispatch($video);
        ConvertVideoFormat::dispatch($video);
    }

    public function deleted(Video $video)
    {
        Storage::disk('public')->delete(
            $video->only('video_path', 'still_path')
        );
    }
}
