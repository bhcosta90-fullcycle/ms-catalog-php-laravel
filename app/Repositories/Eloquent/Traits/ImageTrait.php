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
                'video_id' => $video->id(),
            ], [
                'path' => $data->path(),
                'type' => (string) ImageTypes::BANNER->value,
            ]);
        }
    }

    protected function updateImageThumb(Video $video, ModelsVideo $model)
    {
        if ($data = $video->thumbFile()) {
            $model->thumb()->updateOrCreate([
                'video_id' => $video->id(),
            ], [
                'path' => $data->path(),
                'type' => (string) ImageTypes::THUMB->value,
            ]);
        }
    }

    protected function updateImageHalf(Video $video, ModelsVideo $model)
    {
        if ($data = $video->thumbHalf()) {
            $model->half()->updateOrCreate([
                'video_id' => $video->id(),
            ], [
                'path' => $data->path(),
                'type' => (string) ImageTypes::THUMB_HALF->value,
            ]);
        }
    }
}
