<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserRepository $userRepo)
    {
    }

    public function list()
    {
        $users = $this->userRepo->all();

        return response_success(data: $users->toArray(), extraData: ['total' => $users->count()]);
    }

    public function create(Request $request)
    {
        $params = $request->all();

        $params['password'] = bcrypt($params['password']);

        $data = $this->userRepo->create($params);

        return response_success(data: $data->toArray());
    }
}
