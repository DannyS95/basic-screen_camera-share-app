<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;

class VideoShowController extends Controller
{
    public function __invoke(Video $video)
    {
        return inertia()->render('Videos/Show', [
            'video' => VideoResource::make($video)
        ]);
    }
}
