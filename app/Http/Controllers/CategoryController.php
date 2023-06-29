<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use BRCas\MV\UseCases\Category as UseCase;

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

    public function show(UseCase\ListCategoryUseCase $listCategoryUseCase, string $id){
        $response = $listCategoryUseCase->execute(new UseCase\DTO\ListCategory\Input(
            id: $id,
        ));
        return new CategoryResource($response);
    }
}
