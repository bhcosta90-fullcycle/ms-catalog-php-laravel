<?php

namespace App\Repositories\Eloquent;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use App\Models\Video as ModelsVideo;
use App\Repositories\Presenter\ItemPresenter;
use App\Repositories\Presenter\PaginationPresenter;
use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Builder\Video\UpdateBuilderVideo;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use DateTime;

class VideoRepositoryEloquent implements VideoRepositoryInterface
{
    use Traits\MediaTrait, Traits\ImageTrait;

    public function __construct(protected ModelsVideo $model)
    {
        //
    }

    /**
     * @param Video $entity
     */
    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $model = $this->model::create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'opened' => $entity->opened,
            'duration' => $entity->duration,
            'rating' => $entity->rating->value,
        ]);

        $this->syncRelationships($model, $entity);
        $this->syncImagesAndMedia($model, $entity);

        return $this->toEntity($model);
    }

    public function all(): ItemInterface
    {
        return new ItemPresenter($this->model->get());
    }

    public function paginate(): PaginateInterface
    {
        return new PaginationPresenter($this->model
            ->with([
                'categories',
                'genres',
                'castMember',
                'video',
                'trailer',
                'banner',
                'thumb',
                'half',
            ])
            ->paginate());
    }

    public function getById(string $id): EntityAbstract
    {
        $model = $this->findByModel($id);
        return $this->toEntity($model);
    }

    /**
     * @param Video $entity
     */
    public function update(EntityAbstract $entity): EntityAbstract
    {
        $model = $this->findByModel($entity->id);
        $model->update([
            'title' => $entity->title,
            'description' => $entity->description,
        ]);
        $this->syncRelationships($model, $entity);
        $this->syncImagesAndMedia($model, $entity);

        return $this->toEntity($model);
    }

    /**
     * @param Video $video
     */
    public function delete(EntityAbstract $video): bool
    {
        $model = $this->findByModel($video->id);
        return $model->delete();
    }

    public function updateMedia(Video $video): Video
    {
        $model = $this->findByModel($video->id);
        $this->updateMediaTrailer($video, $model);
        $this->updateMediaVideo($video, $model);
        return $this->toEntity($model);
    }

    protected function findByModel(string $id): ModelsVideo
    {
        if (!$model = $this->model->find($id)) {
            throw new EntityNotFoundException();
        }

        return $model;
    }

    protected function syncRelationships(ModelsVideo $entity, Video $video)
    {
        $entity->categories()->sync($video->categories);
        $entity->genres()->sync($video->genres);
        $entity->castMember()->sync($video->castMembers);
    }

    protected function syncImagesAndMedia(ModelsVideo $entity, Video $video)
    {
        $this->updateMediaTrailer($video, $entity);
        $this->updateMediaVideo($video, $entity);
        $this->updateImageBanner($video, $entity);
        $this->updateImageThumb($video, $entity);
        $this->updateImageHalf($video, $entity);
    }

    protected function toEntity(ModelsVideo $model): Video
    {
        $domain = new Video(
            id: new Uuid($model->id),
            title: $model->title,
            description: $model->description,
            yearLaunched: $model->year_launched,
            duration: $model->duration,
            opened: $model->opened,
            rating: Rating::from($model->rating),
            createdAt: new DateTime($model->created_at),
            categories: $model->categories->pluck('id')->toArray(),
            genres: $model->genres->pluck('id')->toArray(),
            castMembers: $model->castMember->pluck('id')->toArray(),
        );

        $builder = new UpdateBuilderVideo();
        $builder->createEntity($domain);

        if ($data = $model->trailer) {
            $builder->addMediaTrailer(
                path: $data->file_path,
                status: MediaStatus::from($data->media_status),
                encoded: $data->encoded_path,
            );
        }

        if ($data = $model->video) {
            $builder->addMediaVideo(
                path: $data->file_path,
                status: MediaStatus::from($data->media_status),
                encoded: $data->encoded_path,
            );
        }

        if ($data = $model->banner) {
            $builder->addImageBanner($data->path);
        }

        if ($data = $model->thumb) {
            $builder->addImageThumb($data->path);
        }

        if ($data = $model->half) {
            $builder->addImageHalf($data->path);
        }

        return $builder->getEntity();
    }
}
