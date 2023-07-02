<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\ImageTypes;
use App\Models\Video as ModelsVideo;
use BRCas\MV\Domain\Entity\Video;

trait ImageTrait
{
    protected function updateImageBanner(Video $video, ModelsVideo $model)
    {
        if ($data = $video->bannerFile()) {
            $model->banner()->updateOrCreate([
                'path' => $data->path(),
                'type' => (string) ImageTypes::BANNER->value,
            ], [
                'video_id' => $video->id(),
            ]);
        }
    }

    protected function updateImageThumb(Video $video, ModelsVideo $model)
    {
        if ($data = $video->thumbFile()) {
            $model->thumb()->updateOrCreate([
                'path' => $data->path(),
                'type' => (string) ImageTypes::THUMB->value,
            ], [
                'video_id' => $video->id(),
            ]);
        }
    }

    protected function updateImageHalf(Video $video, ModelsVideo $model)
    {
        if ($data = $video->thumbHalf()) {
            $model->half()->updateOrCreate([
                'path' => $data->path(),
                'type' => (string) ImageTypes::THUMB_HALF->value,
            ], [
                'video_id' => $video->id(),
            ]);
        }
    }
}
