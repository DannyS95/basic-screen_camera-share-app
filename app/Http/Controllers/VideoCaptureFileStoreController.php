<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ConvertVideoFormat;
use Illuminate\Http\UploadedFile;
use App\Jobs\GenerateVideoPreviewImage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class VideoCaptureFileStoreController extends Controller
{
    public function __invoke(Request $request, Video $video)
    {
        $request->request->add([
            'chunk' => $request->headers->get('Uploader-Chunk-Number'),
            'chunks' => $request->headers->get('Uploader-Chunks-Total')
        ]);

        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

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
