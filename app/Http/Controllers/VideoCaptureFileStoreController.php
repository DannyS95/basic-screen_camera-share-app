<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ConvertVideoFormat;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\VideoFileStoreRequest;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;

class VideoCaptureFileStoreController extends Controller
{
    public function __invoke(VideoFileStoreRequest $request, Video $video)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class,
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->storeAndAttachFile($save->getFile(), $video);
        }

        $save->handler();
    }

    protected function storeAndAttachFile(UploadedFile $file, Video $video)
    {
        $video->update([
            'video_path' => $file->storeAs('videos', Str::uuid(), 'public')
        ]);

        ConvertVideoFormat::dispatch($video);
    }
}
