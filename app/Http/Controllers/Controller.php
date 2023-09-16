<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SFVEL-ID Documentation",
 *      description="L5 Swagger OpenApi description",
 *
 *      @OA\Contact(
 *          email="tien.nguyentat.1@gmail.com"
 *      ),
 *
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="SFVEL-ID API Server"
 * )
 *
 * @OA\Schema(
 *     schema="Response",
 *
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
