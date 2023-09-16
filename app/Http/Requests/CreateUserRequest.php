<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CreateUserRequest extends ApiBaseRequest
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
            'email' => 'string|email|unique:users',
            'password' => 'required|string|max:255',
            'status' => Rule::in([0, 1]),
            'avatar' => 'file',
            'role_ids' => 'required|array',
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
