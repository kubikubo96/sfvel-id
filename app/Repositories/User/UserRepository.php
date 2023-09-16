<?php

namespace App\Repositories\User;

use App\Models\User;

/**
 * Class UserRepository
 */
class UserRepository extends \App\Repositories\Base\BaseRepository
{
    /**
     * @return string
     */
    public function getModelClass()
    {
        return User::class;
    }
}
