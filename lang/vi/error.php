<?php

use App\Services\Http\HttpErrorCode;

return [
    HttpErrorCode::CODE_1000 => 'Có lỗi xảy ra, vui lòng liên hệ Admin!',
    HttpErrorCode::CODE_1001 => 'Missing Headers.',
    HttpErrorCode::CODE_1002 => 'Missing Parameters.',
    HttpErrorCode::CODE_1003 => 'Invalid offset or limit',
    HttpErrorCode::CODE_1005 => 'Invalid Timezone',
    HttpErrorCode::CODE_1006 => 'Bạn đã yêu cầu vượt quá giới hạn, vui lòng thử lại sau :retryAfter.',
    HttpErrorCode::CODE_1007 => 'Vui lòng cập nhật phiên bản mới nhất để sử dụng hết các tính năng của app.',
    HttpErrorCode::CODE_1009 => 'Yêu cầu bị trì hoãn, vui lòng thử lại sau.',
    HttpErrorCode::CODE_1010 => 'Không tồn tại link hoặc link đã hết hạn.',
    HttpErrorCode::CODE_1011 => 'Không có dữ liệu phù hợp.',
    HttpErrorCode::CODE_1012 => 'Too many attempts!',

    HttpErrorCode::CODE_1100 => 'Unauthorized',
    HttpErrorCode::CODE_1101 => 'Bạn không có quyền truy cập hay thao tác với chức năng hoặc dữ liệu này.',
    HttpErrorCode::CODE_1102 => 'Unprocessable Entity',
    HttpErrorCode::CODE_1103 => 'Authentication Failed',
    HttpErrorCode::CODE_1104 => 'Không tìm thấy link truy cập.',
    HttpErrorCode::CODE_1106 => 'IP không có quyền truy cập.',
];
