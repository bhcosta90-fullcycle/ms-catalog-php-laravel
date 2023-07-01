<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Http\Resources\GenreResource;
use BRCas\MV\UseCases\Genre as UseCase;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function index(UseCase\ListGenresUseCase $createGenreUseCase)
    {
        $response = $createGenreUseCase->execute();
        return GenreResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'current_page' => $response->current_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ]
            ]);
    }

    public function show(UseCase\ListGenreUseCase $listGenreUseCase, string $id)
    {
        $response = $listGenreUseCase->execute(new UseCase\DTO\GenreInput(
            id: $id,
        ));
        return new GenreResource($response);
    }

    public function store(UseCase\CreateGenreUseCase $createGenreUseCase, GenreRequest $request)
    {
        $response = $createGenreUseCase->execute(new UseCase\DTO\CreateGenre\Input(
            name: $request->name,
            isActive: $request->is_active ?? true,
            categories: $request->categories ?: [],
        ));

        return (new GenreResource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UseCase\UpdateGenreUseCase $updateGenreUseCase, GenreRequest $request, string $id)
    {
        $response = $updateGenreUseCase->execute(new UseCase\DTO\UpdateGenre\Input(
            id: $id,
            name: $request->name,
            isActive: $request->is_active ?? true,
            categories: $request->categories ?: [],
        ));

        return new GenreResource($response);
    }

    public function destroy(UseCase\DeleteGenreUseCase $deleteGenreUseCase, string $id)
    {
        $deleteGenreUseCase->execute(new UseCase\DTO\GenreInput(
            id: $id,
        ));

        return response()->noContent();
    }
}
