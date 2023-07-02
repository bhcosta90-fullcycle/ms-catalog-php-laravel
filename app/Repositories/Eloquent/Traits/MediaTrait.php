<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\MediaTypes;
use App\Models\Video as ModelsVideo;
use BRCas\MV\Domain\Entity\Video;

trait MediaTrait
{
    protected function updateMediaTrailer(Video $entity, ModelsVideo $model)
    {
        if ($trailer = $entity->trailerFile()) {
            $action = $model->trailer()->first() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->path,
                'media_status' => (string) $trailer->status->value,
                'encoded_path' => $trailer->encoded,
                'type' => (string) MediaTypes::TRAILER->value,
            ]);
        }
    }

    protected function updateMediaVideo(Video $entity, ModelsVideo $model) {
        if ($mediaVideo = $entity->videoFile()) {
            $action = $model->video()->first() ? 'update' : 'create';
            $model->video()->{$action}([
                'file_path' => $mediaVideo->path,
                'media_status' => (string) $mediaVideo->status->value,
                'encoded_path' => $mediaVideo->encoded,
                'type' => (string) MediaTypes::VIDEO->value,
            ]);
        }
    }
}
