<?php

namespace App\Http\Controllers;

use App\Adapter\ApiAdapter;
use App\Http\Requests\Video\StoreRequest;
use App\Http\Requests\Video\UpdateRequest;
use BRCas\MV\UseCases\Video as UseCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class VideoController extends Controller
{
    public function index(UseCase\ListVideosUseCase $listVideosUseCase)
    {
        $response = $listVideosUseCase->execute();

        return (new ApiAdapter($response))->toJson();
    }

    public function show(UseCase\ListVideoUseCase $listVideoUseCase, string $id)
    {
        $response = $listVideoUseCase->execute(new UseCase\DTO\ListVideoInput(id: $id));

        return ApiAdapter::json($response);
    }

    public function store(UseCase\CreateVideoUseCase $createVideoUseCase, StoreRequest $request)
    {
        $response = $createVideoUseCase->execute(new UseCase\DTO\CreateVideoInput(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: $request->opened,
            rating: $request->rating,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: $this->getArrayFromImage($request->file('video_file')),
            trailerFile: $this->getArrayFromImage($request->file('trailer_file')),
            bannerFile: $this->getArrayFromImage($request->file('banner_file')),
            thumbFile: $this->getArrayFromImage($request->file('thumb_file')),
            thumbHalf: $this->getArrayFromImage($request->file('half_file')),
        ));

        return ApiAdapter::json($response, Response::HTTP_CREATED);
    }

    public function update(UseCase\UpdateVideoUseCase $updateVideoUseCase, string $id, UpdateRequest $request)
    {
        $response = $updateVideoUseCase->execute(new UseCase\DTO\UpdateVideoInput(
            id: $id,
            title: $request->title,
            description: $request->description,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: $this->getArrayFromImage($request->file('video_file')),
            trailerFile: $this->getArrayFromImage($request->file('trailer_file')),
            bannerFile: $this->getArrayFromImage($request->file('banner_file')),
            thumbFile: $this->getArrayFromImage($request->file('thumb_file')),
            thumbHalf: $this->getArrayFromImage($request->file('half_file')),
        ));

        return ApiAdapter::json($response);
    }

    public function destroy(UseCase\DeleteVideoUseCase $deleteVideoUseCase, string $id)
    {
        $deleteVideoUseCase->execute(new UseCase\DTO\ListVideoInput(
            id: $id,
        ));

        return response()->noContent();
    }

    protected function getArrayFromImage(?UploadedFile $file): ?array
    {
        if ($file) {
            return [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'type' => $file->getType(),
            ];
        }

        return null;
    }
}
