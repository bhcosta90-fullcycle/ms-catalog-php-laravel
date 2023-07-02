<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\MediaTypes;
use App\Models\Video as ModelsVideo;
use BRCas\MV\Domain\Entity\Video;

trait MediaTrait
{
    protected function updateMediaTrailer(Video $video, ModelsVideo $model)
    {
        if ($data = $video->trailerFile()) {
            $model->trailer()->updateOrCreate([
                'video_id' => $video->id(),
            ], [
                'file_path' => $data->path,
                'media_status' => $data->status->value,
                'encoded_path' => $data->encoded,
                'type' => (string) MediaTypes::TRAILER->value,
            ]);
        }
    }

    protected function updateMediaVideo(Video $video, ModelsVideo $model) {
        if ($data = $video->videoFile()) {
            $model->trailer()->updateOrCreate([
                'video_id' => $video->id(),
            ], [
                'file_path' => $data->path,
                'media_status' => $data->status->value,
                'encoded_path' => $data->encoded,
                'type' => (string) MediaTypes::VIDEO->value,
            ]);
        }
    }
}
