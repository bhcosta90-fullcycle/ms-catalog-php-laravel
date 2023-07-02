<?php

namespace App\Adapter;

use App\Http\Resources\DefaultResource;
use BRCas\CA\Repository\PaginateInterface;
use Illuminate\Http\Response;

class ApiAdapter
{
    public function __construct(
        protected PaginateInterface $response
    ) {
        //
    }

    public function toJson()
    {
        return DefaultResource::collection($this->response->items())
            ->additional([
                'meta' => [
                    'total' => $this->response->total(),
                    'last_page' => $this->response->lastPage(),
                    'first_page' => $this->response->firstPage(),
                    'current_page' => $this->response->currentPage(),
                    'per_page' => $this->response->perPage(),
                    'to' => $this->response->to(),
                    'from' => $this->response->from(),
                ]
            ]);
    }

    public static function json(object $data, int $status = Response::HTTP_OK)
    {
        return (new DefaultResource($data))->response()->setStatusCode($status);
    }
}
