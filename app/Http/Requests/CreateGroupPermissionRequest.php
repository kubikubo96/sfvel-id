<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CreateGroupPermissionRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'max:255',
            'status' => Rule::in([0, 1]),
            'is_default' => Rule::in([0, 1]),
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
