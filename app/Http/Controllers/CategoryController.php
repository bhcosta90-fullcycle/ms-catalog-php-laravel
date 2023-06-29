<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use BRCas\MV\UseCases\Category as UseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(UseCase\ListCategoriesUseCase $createCategoryUseCase)
    {
        $response = $createCategoryUseCase->execute(new UseCase\DTO\ListCategories\Input());
        return CategoryResource::collection(collect($response->items))
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

    public function show(UseCase\ListCategoryUseCase $listCategoryUseCase, string $id)
    {
        $response = $listCategoryUseCase->execute(new UseCase\DTO\CategoryInput(
            id: $id,
        ));
        return new CategoryResource($response);
    }

    public function store(UseCase\CreateCategoryUseCase $createCategoryUseCase, CategoryRequest $request)
    {
        $response = $createCategoryUseCase->execute(new UseCase\DTO\CreateCategory\Input(
            name: $request->name,
            description: $request->description,
            isActive: $request->is_active ?? true,
        ));

        return (new CategoryResource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UseCase\UpdateCategoryUseCase $updateCategoryUseCase, CategoryRequest $request, string $id) {
        $response = $updateCategoryUseCase->execute(new UseCase\DTO\UpdateCategory\Input(
            id: $id,
            name: $request->name,
            description: $request->description,
            isActive: $request->is_active ?? true,
        ));

        return new CategoryResource($response);
    }

    public function destroy(UseCase\DeleteCategoryUseCase $deleteCategoryUseCase, string $id) {
        $deleteCategoryUseCase->execute(new UseCase\DTO\CategoryInput(
            id: $id,
        ));

        return response()->noContent();
    }
}
