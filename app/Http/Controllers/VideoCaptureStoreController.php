<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoStoreRequest;

class VideoCaptureStoreController extends Controller
{
    public function __invoke(VideoStoreRequest $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        
        $video = $request->user()->videos()->create($request->only('title', 'description'));


        return response()->json([
            'id' => $video->id
        ]);
    }
}
