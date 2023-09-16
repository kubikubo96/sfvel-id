<?php

namespace App\Http\Requests;

use App\Services\Http\HttpErrorCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class BaseApiRequest
 */
abstract class ApiBaseRequest extends LaravelFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => HttpErrorCode::CODE_400,
                'errors' => $validator->errors()->getMessages(),
            ], 400));
    }
}
