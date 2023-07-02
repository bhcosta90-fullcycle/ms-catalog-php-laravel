<?php

namespace App\Http\Controllers;

use App\Http\Requests\CastMemberRequest;
use App\Http\Resources\CastMemberResource;
use BRCas\MV\UseCases\CastMember as UseCase;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(UseCase\ListCastMembersUseCase $createCastMemberUseCase)
    {
        $response = $createCastMemberUseCase->execute();

        return CastMemberResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'current_page' => $response->current_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ],
            ]);
    }

    public function show(UseCase\ListCastMemberUseCase $listCastMemberUseCase, string $id)
    {
        $response = $listCastMemberUseCase->execute(new UseCase\DTO\CastMemberInput(
            id: $id,
        ));

        return new CastMemberResource($response);
    }

    public function store(UseCase\CreateCastMemberUseCase $createCastMemberUseCase, CastMemberRequest $request)
    {
        $response = $createCastMemberUseCase->execute(new UseCase\DTO\CreateCastMember\Input(
            name: $request->name,
            type: $request->type,
            isActive: $request->is_active ?? true,
        ));

        return (new CastMemberResource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UseCase\UpdateCastMemberUseCase $updateCastMemberUseCase, CastMemberRequest $request, string $id)
    {
        $response = $updateCastMemberUseCase->execute(new UseCase\DTO\UpdateCastMember\Input(
            id: $id,
            name: $request->name,
            isActive: $request->is_active ?? true,
        ));

        return new CastMemberResource($response);
    }

    public function destroy(UseCase\DeleteCastMemberUseCase $deleteCastMemberUseCase, string $id)
    {
        $deleteCastMemberUseCase->execute(new UseCase\DTO\CastMemberInput(
            id: $id,
        ));

        return response()->noContent();
    }
}
