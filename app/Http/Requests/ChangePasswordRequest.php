<?php

namespace App\Http\Requests;

class ChangePasswordRequest extends ApiBaseRequest
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
            'old_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
            're_new_password' => 'required|same:new_password|string|max:255',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            're_new_password.required' => 'Vui lòng nhập lại mật khẩu mới',
            're_new_password.same' => 'Mật khẩu mới và mật khẩu nhập lại không trùng khớp',
        ];
    }
}
