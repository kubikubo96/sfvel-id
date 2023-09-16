<?php

namespace App\Repositories\Base;

/**
 * Interface BaseWriteRepositoryInterface.
 */
interface IBaseWriteRepository
{
    /**
     * find by id.
     */
    public function find($id);

    /**
     * create data.
     */
    public function create(array $attributes);

    /**
     * Insert many records.
     */
    public function insert(array $records);

    /**
     * update data.
     */
    public function update($model, array $attributes);

    /**
     * delete data.
     */
    public function delete($model);
}
