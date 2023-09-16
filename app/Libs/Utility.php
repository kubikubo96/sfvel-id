<?php

use App\Services\Http\HttpErrorCode;

if (! function_exists('is_dev')) {
    function is_dev(): bool
    {
        $environment = config('environment.domain');
        if (env('APP_ENV') == 'production' || in_array($_SERVER['SERVER_NAME'] ?? '', $environment)) {
            return false;
        }

        return true;
    }
}

if (! function_exists('auth_user')) {
    function auth_user()
    {
        return auth()->user();
    }
}

if (! function_exists('base_url')) {
    /**
     * Lấy domain hiện tại.
     */
    function base_url(string $path = ''): string
    {
        $base_url = (isset($_SERVER['HTTP_HOST'])) ? 'https://'.$_SERVER['HTTP_HOST'] : env('APP_URL');
        if ($path) {
            $base_url .= $path;
        }

        return $base_url;
    }
}

if (! function_exists('response_success')) {
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    function response_success(array $data = [], string $message = null, array $extraData = [])
    {
        $responseData = [
            'success' => true,
            'message' => ! empty($message) ? $message : 'Thành công!',
            'data' => $data ?? new stdClass(),
        ];

        $responseData = array_merge($responseData, $extraData);

        return response()->json($responseData);
    }
}

if (! function_exists('response_error')) {
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    function response_error(int $code = 1000, string $message = null, array $errors = [], array $extraData = [], int $status = 200)
    {
        if ($message instanceof Exception && ! is_dev()) {
            $message = null;
        }
        $res = [
            'success' => false,
            'message' => ! empty($message) ? $message : HttpErrorCode::getErrorMessage($code),
            'code' => $code,
            'errors' => $errors,
        ];

        $res = array_merge($res, $extraData);

        return response()->json($res, $status);
    }
}

if (! function_exists('remove_accents')) {
    function remove_accents($str)
    {
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/', 'A', $str);
        $str = preg_replace('/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/', 'E', $str);
        $str = preg_replace('/(Ì|Í|Ị|Ỉ|Ĩ)/', 'I', $str);
        $str = preg_replace('/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/', 'O', $str);
        $str = preg_replace('/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/', 'U', $str);
        $str = preg_replace('/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/', 'Y', $str);
        $str = preg_replace('/(Đ)/', 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
        $str = preg_replace('/( )/', '-', $str);

        return $str;
    }
}

if (! function_exists('make_slug')) {
    function make_slug($str, $type = 'post', $delimiter = '-')
    {
        $str = remove_accents($str);
        try {
            $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        } catch (Exception $e) {
            $slug = $str;
        }
        if ($type == 'post') {
            $date = now();

            return $slug.'-'.date('G', strtotime($date)).date('i', strtotime($date)).date('d', strtotime($date)).date('m', strtotime($date)).date('Y', strtotime($date));
        }

        return $slug;
    }
}

if (! function_exists('base64url_encode')) {
    function base64url_encode($str = '')
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (! function_exists('is_jwt_valid')) {
    function is_jwt_valid($jwt_token, $secret, $algo = 'SHA512')
    {
        // split the jwt token
        $tokenParts = explode('.', $jwt_token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        // build a signature based on the header and payload using the secret
        $base64_url_header = base64url_encode($header);
        $base64_url_payload = base64url_encode($payload);
        $signature = hash_hmac($algo, $base64_url_header.'.'.$base64_url_payload, $secret, true);
        $base64_url_signature = base64url_encode($signature);

        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);

        if ($is_token_expired || ! $is_signature_valid) {
            return false;
        } else {
            return true;
        }
    }
}

if (! function_exists('decode_jwt')) {
    /**
     * Decode JWT.
     *
     * @return array
     */
    function decode_jwt($token)
    {
        if (empty($token)) {
            return [];
        }
        $tokenParts = explode('.', $token);
        if (empty($tokenParts[1])) {
            return [];
        }
        $tokenPayload = base64_decode($tokenParts[1]);

        return json_decode($tokenPayload, true);
    }
}

if (! function_exists('build_url')) {
    function build_url($path = '', $params = []): string
    {
        return base_url($path).'?'.http_build_query($params);
    }
}

if (! function_exists('get_protocol')) {
    function get_protocol(): string
    {
        return ((! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || $_SERVER['SERVER_PORT'] == 443
            || (! empty($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)) ? 'https://' : 'http://';
    }
}

if (! function_exists('platform_path')) {
    function platform_path($name = '', $path = ''): ?string
    {
        $platformDir = base_path('platform');

        if (empty($name)) {
            return file_exists($platformDir) ? $platformDir : null;
        }

        $nameVariants = [
            $name,
            mb_strtolower($name),
            mb_convert_case($name, MB_CASE_TITLE, 'UTF-8'),
            mb_strtoupper($name),
        ];

        foreach ($nameVariants as $variant) {
            $tempPlatformDir = $platformDir.DIRECTORY_SEPARATOR.$variant;
            if (file_exists($tempPlatformDir)) {
                $platformDir = $tempPlatformDir;
                break;
            }
        }

        if (! isset($tempPlatformDir)) {
            return null;
        }

        return $platformDir.(! empty($path) ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('get_ip')) {
    function get_ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? \Illuminate\Http\Request::ip();
        if (! empty($_SERVER['X-ORIGIN-CLIENT-IP'])) {
            $ip = $_SERVER['X-ORIGIN-CLIENT-IP'];
        } elseif (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ip;
    }
}

if (! function_exists('remove_html')) {
    function remove_html($string)
    {
        $string = preg_replace('/<script.*?\>.*?<\/script>/si', ' ', $string);
        $string = preg_replace('/<style.*?\>.*?<\/style>/si', ' ', $string);
        $string = preg_replace('/<.*?\>/si', ' ', $string);
        $string = str_replace('&nbsp;', ' ', $string);
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = str_replace([chr(9), chr(10), chr(13)], ' ', $string);
        for ($i = 0; $i <= 5; $i++) {
            $string = str_replace('  ', ' ', $string);
        }

        return $string;
    }
}

if (! function_exists('get_ip_range')) {
    /**
     * Lấy IP đầu và cuối của dải IP.
     *
     * @param  string  $cidr
     * @return array
     */
    function get_ip_range($cidr)
    {
        [$ip, $mask] = explode('/', $cidr);

        $maskBinStr = str_repeat('1', $mask).str_repeat('0', 32 - $mask);      // net mask binary string
        $inverseMaskBinStr = str_repeat('0', $mask).str_repeat('1', 32 - $mask); // inverse mask

        $ipLong = ip2long($ip);
        $ipMaskLong = bindec($maskBinStr);
        $inverseIpMaskLong = bindec($inverseMaskBinStr);
        $netWork = $ipLong & $ipMaskLong;

        $start = $netWork + 1; // ignore network ID(eg: 192.168.1.0)

        $end = ($netWork | $inverseIpMaskLong) - 1; // ignore brocast IP(eg: 192.168.1.255)

        return ['firstIP' => $start, 'lastIP' => $end];
    }
}

if (! function_exists('fetch_graphql')) {
    /**
     * Get data api graphql.
     *
     * @param  string  $query
     * @param  array  $headers
     * @param  string  $domain
     */
    function fetch_graphql($query, $headers, $domain)
    {
        $urlApi = $domain.'/graphql';

        $data = [
            'query' => $query,
        ];
        $data = http_build_query($data);
        $response = Http::withHeaders($headers)
            ->connectTimeout(10)
            ->timeout(10)
            ->retry(2, 1000)
            ->post($urlApi, $data);

        return $response->json();
    }
}

if (! function_exists('is_json')) {
    /**
     * Check string là json.
     */
    function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}

if (! function_exists('format_number')) {
    /**
     * Phân cách phần ngàn và sau thập phân bởi ký tự.
     */
    function format_number($number, $decimals = 0, $separator = ',', $thousands = '.')
    {
        $point = strpos(''.$number, '.');
        if ($point !== false && $decimals != 0) {
            $tmp = strlen(''.$number) - ($point + 1);
            $decimals = ($tmp <= $decimals) ? $tmp : $decimals;
        }
        if ($point === false) {
            $decimals = 0;
        }

        return number_format($number, $decimals, $separator, $thousands);
    }
}

if (! function_exists('build_url')) {
    /**
     * Xây dựng full url có path và params truyền vào.
     *
     * @param  mixed  $path
     * @param  mixed  $params
     */
    function build_url($path = '', $params = []): string
    {
        return base_url($path).'?'.http_build_query($params);
    }
}

if (! function_exists('get_all_file')) {
    /**
     * Lấy tất cả các file trong folder.
     *
     * @return array|false
     */
    function get_all_file(string $pattern): bool|array
    {
        $files = glob($pattern, GLOB_BRACE);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, get_all_file($dir.'/'.basename($pattern)));
        }

        return $files;
    }
}

if (! function_exists('get_all_class')) {
    /**
     * Lấy tất cả các class của file.
     *
     * @return \Illuminate\Support\Collection
     */
    function get_all_class(string $pattern)
    {
        $files = get_all_file($pattern);

        return collect($files)
            ->map(fn ($file) => [basename($file, '.php'), $file])
            ->filter(fn ($class) => ! preg_match('/^I[A-Z]/', $class[0]));
    }
}

if (! function_exists('get_sub_path')) {
    /**
     * Lấy sub path file.
     */
    function get_sub_path(string $file, string $folder = 'Modules'): string
    {
        $parts = explode('/', $file);
        $index = array_search($folder, $parts);
        if ($index !== false) {
            $subPath = array_slice($parts, $index);
            $result = implode('/', $subPath);
            if (! is_dir($result)) {
                $result = dirname($result);
            }

            return str_replace('/', '\\', $result);
        }

        return '';
    }
}

if (! function_exists('floor_decimal')) {
    /**
     * Làm tròn xuống theo decimal.
     *
     * @param  mixed  $number
     * @return int
     */
    function floor_decimal(float|int $number, int $decimal = 2)
    {
        $coefficient = pow(10, $decimal);

        return floor($number * $coefficient) / $coefficient;
    }
}

if (! function_exists('hide_phone')) {
    function hide_phone($input_tel)
    {
        return preg_replace("/\d{3}(?=\d{4}$)/", '***', $input_tel);
    }
}

if (! function_exists('hide_phone_in_str')) {
    function hide_phone_in_str(string $str)
    {
        $pattern = "/\d{9,11}/";
        preg_match_all($pattern, $str, $matches);
        foreach ($matches[0] as $match) {
            $str = str_replace($match, hide_phone($match), $str);
        }

        return $str;
    }
}

if (! function_exists('divide_numbers')) {
    /**
     * Chia 2 số.
     */
    function divide_numbers(int|float $number1 = 0, int|float $number2 = 0, int $decimals = null, bool $down = true)
    {
        if ($number1 == 0 || $number2 == 0) {
            return 0;
        }

        $number = $number1 / $number2;
        if (! $decimals) {
            return $number;
        }

        return format_decimals($number, $decimals, $down);
    }
}

if (! function_exists('format_decimals')) {
    /**
     * Làm tròn lên/xuống theo decimal.
     */
    function format_decimals(int|float $number, int $decimals = 2, bool $down = true)
    {
        if ($number == 0) {
            return 0;
        }

        if ($down) {
            return floor_decimal($number, $decimals);
        }

        return floatval(sprintf('%.'.$decimals.'f', $number));
    }
}

if (! function_exists('floor_ten')) {
    /**
     * Làm tròn xuống hàng chục gần nhất.
     *
     * @param  mixed  $number
     * @return int
     */
    function floor_ten(float|int $number)
    {
        if ($number <= 0) {
            return 0;
        }

        return floor($number / 10) * 10;
    }
}
