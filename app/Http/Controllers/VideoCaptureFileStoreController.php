<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Str;
use App\Jobs\ConvertVideoFormat;
use Illuminate\Http\UploadedFile;
use App\Jobs\GenerateVideoPreviewImage;
use App\Http\Requests\VideoFileStoreRequest;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;

class VideoCaptureFileStoreController extends Controller
{
    public function __invoke(VideoFileStoreRequest $request, Video $video)
    {
        $file = $request->only('file')['file'];

        $receiver = new FileReceiver(
            $file,
            $request,
            ContentRangeUploadHandler::class,
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->storeAndAttachFile($save->getFile(), $video);
        }

        $save->handler();
    }

    private function storeAndAttachFile(UploadedFile $file, Video $video)
    {
        $video->update([
            'video_path' => $file->storePubliclyAs('videos', Str::uuid(), 'public')
        ]);

        GenerateVideoPreviewImage::dispatch($video);
        ConvertVideoFormat::dispatch($video);
    }
}
