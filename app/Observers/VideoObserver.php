<?php

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VideoObserver
{
    public function creating(Video $video)
    {
        $video->uuid = Str::uuid();
    }
}
