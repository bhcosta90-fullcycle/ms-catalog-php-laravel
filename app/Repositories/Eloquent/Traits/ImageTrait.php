<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\ImageTypes;
use App\Models\Video as ModelsVideo;
use BRCas\MV\Domain\Entity\Video;

trait ImageTrait
{
    protected function updateImageBanner(Video $entity, ModelsVideo $model)
    {
        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->first() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path(),
                'type' => (string) ImageTypes::BANNER->value,
            ]);
        }
    }

    protected function updateImageThumb(Video $video, ModelsVideo $model)
    {
        if ($thumb = $video->thumbFile()) {
            $action = $model->thumb()->first() ? 'update' : 'create';
            $model->thumb()->{$action}([
                'path' => $thumb->path(),
                'type' => (string) ImageTypes::THUMB->value,
            ]);
        }
    }

    protected function updateImageHalf(Video $video, ModelsVideo $model)
    {
        if ($thumbHalf = $video->thumbHalf()) {
            $action = $model->thumbHalf()->first() ? 'update' : 'create';
            $model->thumbHalf()->{$action}([
                'path' => $thumbHalf->path(),
                'type' => (string) ImageTypes::THUMB_HALF->value,
            ]);
        }
    }
}
