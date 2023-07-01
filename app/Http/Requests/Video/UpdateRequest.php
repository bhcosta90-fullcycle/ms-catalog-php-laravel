<?php

namespace App\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min: 3', 'max: 255'],
            'description' => ['required', 'min: 3', 'max: 255'],
            'categories' => 'required|array|exists:categories,id,deleted_at,NULL',
            'genres' => ['required', 'array', 'exists:genres,id,deleted_at,NULL'],
            'cast_members' => 'required|array|exists:cast_members,id,deleted_at,NULL',
            'thumb_file' => 'nullable|image', //5MB
            'thumb_half_file' => 'nullable|image', //5MB
            'banner_file' => 'nullable|image', //10MB
            'trailer_file' => 'nullable|mimetypes:video/mp4', //1GB
            'video_file' => 'nullable|mimetypes:video/mp4', //50GB
        ];
    }
}
