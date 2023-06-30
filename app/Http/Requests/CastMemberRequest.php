<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastMemberRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'min:3', 'max:255'],
        ];

        if (!request()->route('cast_member')) {
            $rules['type'] = ['required', 'int'];
        }

        return $rules;
    }
}
