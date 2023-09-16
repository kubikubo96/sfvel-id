<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait ReadRepositoryTrait
{
    /**
     * Get all.
     */
    public function all()
    {
        return $this->_model->all();
    }

    /**
     * Get one.
     *
     * @param  mixed  $id
     * @param  mixed  $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->_model->find($id, $columns);
    }

    /**
     * Query data.
     *
     * @param  array  $options
     * @param  array  $order
     */
    public function query($options = [], $order = [])
    {
        $query = $this->_model;
        if (! empty($options)) {
            $query = $this->queryOptions($options, $query);
        }

        return $this->order($order, $query);
    }

    /**
     * Get list.
     *
     * @param  array  $options
     * @param  mixed  $columns
     * @return mixed
     */
    public function get($options = [], $columns = ['*'])
    {
        return $this->query($options)->get($columns);
    }

    /**
     * Get first.
     *
     * @param  array  $options
     * @param  mixed  $columns
     */
    public function first($options = [], $columns = ['*'])
    {
        return $this->query($options)->first($columns);
    }

    /**
     * Query paginate.
     *
     * @param  mixed  $query
     * @return mixed
     */
    public function queryPaginate($query, int $page = 1, int $limit = 20)
    {
        $query = $query->skip(($page - 1) * $limit);
        $query = $query->take($limit);

        return $query;
    }

    /**
     * Phân trang theo query.
     *
     * @param  int  $page
     * @param  int  $limit
     * @param  mixed  $query
     * @return mixed
     */
    public function paginatedQuery($query, $page = 1, $limit = 20)
    {
        return $this->queryPaginate($query, $page, $limit)->get();
    }

    /**
     * Phân trang theo function query.
     *
     * @param  int  $page
     * @param  int  $limit
     * @param  array  $order
     * @param  mixed  $options
     */
    public function paginate($options, $page = 1, $limit = 20, $order = []): array
    {
        $query = $this->query($options, $order);
        $data['total'] = $query->count();
        $data['data'] = $this->paginatedQuery($query, $page, $limit);

        return $data;
    }

    /**
     * Order query.
     *
     * @param  array  $order
     * @return mixed
     */
    public function order($order = [], $query = null)
    {
        if (! $query) {
            $query = $this->_model;
        }
        if (! empty($order)) {
            return $query->orderBy(array_key_first($order), end($order));
        }

        return $query;
    }

    /**
     * Query options.
     *
     * @param  array  $options
     * @return null|mixed|Model
     */
    public function queryOptions($options = [], $query = null)
    {
        if (! $query) {
            $query = $this->_model;
        }
        foreach ($options as $key => $value) {
            $arrayKey = explode(' ', $key);
            $column = $arrayKey[0];
            $opera = $arrayKey[1] ?? '=';
            if (is_array($value)) {
                $query = $query->whereIn($column, $value);
            } else {
                $query = $query->where($column, $opera, $value);
            }
        }

        return $query;
    }
}
