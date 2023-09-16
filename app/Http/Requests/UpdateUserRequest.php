<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiBaseRequest
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
            'name' => 'string|max:255',
            'email' => 'email',
            'password' => 'max:255',
            'status' => Rule::in([0, 1]),
            'avatar' => 'file',
            'role_ids' => 'array',
            'role_ids.*.name' => 'numeric',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Tên role đã tồn tại.',
            'email.unique' => 'Email đã tồn tại.',
        ];
    }
}
