<?php

namespace App\Exceptions;

use App\Services\Http\HttpErrorCode;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Middleware\ThrottleRequests;

class TooManyAttemptsException extends ThrottleRequests
{
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        $mes = HttpErrorCode::getErrorMessageTrans(HttpErrorCode::CODE_1006, ['retryAfter' => format_time($retryAfter)]);

        return is_callable($responseCallback)
            ? new \HttpResponseException($responseCallback($request, $headers))
            : new ThrottleRequestsException($mes, null, $headers);
    }
}
