<?php

namespace App\Http\Requests;

class CreateRoleRequest extends ApiBaseRequest
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
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'max:255',
            'permission_ids' => 'array',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Tên role đã tồn tại.',
        ];
    }
}
