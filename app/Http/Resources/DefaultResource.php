<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return collect($this->resource)
            ->mapWithKeys(function ($value, $key) {
                $key = trim(strtolower(preg_replace('/[A-Z]/', '_$0', $key)));
                return [
                    $key => $value,
                ];
            });
    }
}
