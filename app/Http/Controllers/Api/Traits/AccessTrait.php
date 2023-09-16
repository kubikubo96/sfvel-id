<?php

namespace App\Http\Controllers\Api\Traits;

/**
 * ScheduleSupport AccessTrait
 */
trait AccessTrait
{
    /**
     * check user access permission
     *
     * @return bool
     */
    protected function can($permission)
    {
        return auth()->user()->can($permission);
    }

    /**
     * @return mixed
     */
    protected function isSuperAdmin()
    {
        return auth()->user()->hasRole('super-admin');
    }
}
