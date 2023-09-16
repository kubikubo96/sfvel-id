<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait WriteRepositoryTrait
{
    /**
     * Get one.
     */
    public function find($id)
    {
        return $this->_model->find($id);
    }

    /**
     * Create.
     */
    public function create(array $attributes)
    {
        $model = $this->_model->newInstance($attributes);
        $model->save();

        return $model->fresh();
    }

    /**
     * Save the model to the database within a transaction.
     *
     * @return bool
     *
     * @throws \Throwable
     */
    public function createOrFail(array $attributes, array $options = [])
    {
        return tap($this->_model->newModelInstance($attributes), fn ($instance) => $instance->saveOrFail($options));
    }

    /**
     * Insert many records.
     */
    public function insert(array $records)
    {
        return $this->_model->insert($records);
    }

    /**
     * Insert many records into the database within a transaction.
     *
     * @return bool
     *
     * @throws \Throwable
     */
    public function insertOrFail(array $records)
    {
        return $this->_model->connection
            ->transaction(fn () => $this->_model->insert($records));
    }

    /**
     * Update.
     *
     * @param  int|Model  $model
     */
    public function update($model, array $attributes)
    {
        if (! $model instanceof Model) {
            $model = $this->find($model);
        }

        if ($model) {
            $model->update($attributes);
        }

        return $model;
    }

    /**
     * Update the model in the database within a transaction.
     *
     * @param  int|Model  $model
     * @return null|Model
     *
     * @throws \Throwable
     */
    public function updateOrFail($model, array $attributes, array $options = [])
    {
        if (! $model instanceof Model) {
            $model = $this->_model->find($model);
        }

        if ($model) {
            $model->updateOrFail($attributes, $options);
        }

        return $model;
    }

    /**
     * Delete the model from the database.
     */
    public function delete($model): bool
    {
        if (! $model instanceof Model) {
            $model = $this->_model->find($model);
        }

        return $model ? $model->delete() : false;
    }

    /**
     * Delete the model from the database within a transaction.
     *
     * @param $model int|Model
     *
     * @throws \Throwable
     */
    public function deleteOrFail($model): bool
    {
        if (! $model instanceof Model) {
            $model = $this->_model->find($model);
        }

        return $model ? $model->deleteOrFail() : false;
    }
}
