<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\JsonResponse;

/**
 * ScheduleSupport ResponseTrait
 */
trait ResponseTrait
{
    /**
     * @param  array  $data
     * @param  int  $code
     * @param  string  $message
     * @return JsonResponse
     */
    protected function responseSuccess($data = [], $total = '', $code = 200, $message = '')
    {
        $response = array_merge_recursive([
            'status' => $code,
            'message' => $message != '' ? $message : $this->getMessage($code),
        ], $this->formatData($data));
        if ($total) {
            $response['total'] = $total;
        }

        return response()->json($response);
    }

    /**
     * @param  int  $code
     * @param  string  $message
     * @return JsonResponse
     */
    protected function responseErrors($statusCode = 400, $message = '', $data = null)
    {
        return response()->json(array_merge_recursive([
            'status' => $statusCode,
            'message' => $message != '' ? $message : $this->getMessage($statusCode),
        ], $this->formatData($data)))->setStatusCode($statusCode);
    }

    /**
     * @param  string  $message
     * @return JsonResponse
     */
    protected function responseBadRequest($message = null)
    {
        return $this->responseErrors('400', $message);
    }

    /**
     * @param  string  $message
     * @return JsonResponse
     */
    protected function responseNotFound($message = null)
    {
        return $this->responseErrors('404', $message);
    }

    /**
     * @param  string  $message
     * @return JsonResponse
     */
    protected function responseInternalServerError($message = null)
    {
        return $this->responseErrors('500', $message);
    }

    /**
     * @return string
     */
    protected function getMessage($code)
    {
        switch ($code) {
            case 400:
                $message = 'Invalid data';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            case 403:
                $message = 'Forbidden';
                break;
            case 404:
                $message = 'Not found';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 200:
                $message = 'Success';
                break;
            default:
                $message = '';
        }

        return $message;
    }

    protected function formatData($data)
    {
        return is_array($data) && array_key_exists('data', $data)
            ? $data
            : ['data' => $data];
    }
}
