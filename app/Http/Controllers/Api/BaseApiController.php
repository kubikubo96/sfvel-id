<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\AccessTrait;
use App\Http\Controllers\Api\Traits\ResponseTrait;
use App\Http\Controllers\Controller;

/**
 * Class BaseApiController
 */
abstract class BaseApiController extends Controller
{
    use AccessTrait, ResponseTrait;
}
