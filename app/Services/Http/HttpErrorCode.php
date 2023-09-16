<?php

namespace App\Services\Http;

/**
 * Class HttpResponseErrorCode.
 */
class HttpErrorCode
{
    /**
     * 10XX: Main App Errors.
     */
    public const CODE_1000 = 1000; // App Server Error, please contact the admin

    public const CODE_1001 = 1001; // Missing Headers

    public const CODE_1002 = 1002; // Missing Parameters

    public const CODE_1003 = 1003; // Invalid offset or limit

    public const CODE_1004 = 1004; // Invalid Locale

    public const CODE_1005 = 1005; // Invalid Timezone

    public const CODE_1006 = 1006; // You exceeded the limit of requests per minute, Please try again after sometime.

    public const CODE_1007 = 1007; // App version

    public const CODE_1009 = 1009; // Yêu cầu bị trì hoãn, vui lòng thử lại sau.

    public const CODE_1010 = 1010; // Không tồn tại link hoặc link đã hết hạn.

    public const CODE_1011 = 1011; // Không có dữ liệu phù hợp

    public const CODE_1012 = 1012; // Too many attempts!

    /**
     * 11XX: Auth Errors.
     */
    public const CODE_1100 = 1100; // Unauthorized

    public const CODE_1101 = 1101; // Bạn không có quyền thực hiện chức năng này. Vui lòng liên hệ Admin.

    public const CODE_1102 = 1102; // Unprocessable Entity

    public const CODE_1103 = 1103; // Authentication Failed

    public const CODE_1104 = 1104; // Not Found

    public const CODE_1106 = 1106; // This IP does not allow access

    public const CODE_1107 = 1107; // Bạn không có quyền truy cập.

    /**
     * 13XX: Session Errors.
     */
    public const CODE_1300 = 1300; // Login is incorrect

    public const CODE_1302 = 1302; // Upload file lên GS lỗi

    public const CODE_1304 = 1304; // User is not exists.

    public const CODE_1305 = 1305; // User is not active.

    /**
     * 14XX: Logic validate Errors.
     */
    public const CODE_400 = 400; // Bad request

    /**
     * Get all error codes.
     */
    public static function ALL_CODES(bool $withConstName = false): array
    {
        // Lấy tất cả các constant của class
        $reflectionClass = new \ReflectionClass(static::class);
        $constants = $reflectionClass->getConstants();

        // Chỉ lấy các constant bắt đầu bằng "CODE_"
        $constants = array_filter($constants, fn ($key) => \Str::startsWith($key, 'CODE_'), ARRAY_FILTER_USE_KEY);

        if ($withConstName) {
            // Trả về array với key là tên constant và value là code, ex: ['CODE_1000' => 1000, 'CODE_1001' => 1001, ...]
            return $constants;
        } else {
            // Chỉ trả về codes array, ex: [1000, 1001, ...]
            return array_values($constants);
        }
    }

    /**
     * @param  mixed  $code
     * @param  mixed  $params
     * @return string
     */
    public static function getErrorMessage($code, $params = [])
    {
        $message = 'Unknown';

        if (in_array($code, static::ALL_CODES())) {
            $getTransMessage = static::getErrorMessageTrans($code, $params);
            $message = ! empty($getTransMessage) ? $getTransMessage : $message;
        }

        return $message;
    }

    /**
     * @param  array  $params
     * @param  mixed  $code
     * @return string
     */
    public static function getErrorMessageTrans($code, $params = [])
    {
        return __('error.'.$code, $params);
    }

    /**
     * Get all error codes with message.
     */
    public static function getErrors(bool $withMessage = true): array
    {
        if ($withMessage) {
            return collect(static::ALL_CODES())->mapWithKeys(function ($code) {
                return [$code => static::getErrorMessageTrans($code)];
            })->toArray();
        } else {
            return static::ALL_CODES();
        }
    }
}
