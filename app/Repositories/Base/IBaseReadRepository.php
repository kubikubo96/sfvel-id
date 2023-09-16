<?php

namespace App\Repositories\Base;

/**
 * Interface BaseReadRepositoryInterface.
 */
interface IBaseReadRepository
{
    /**
     * Get all.
     */
    public function all();

    /**
     * find by id.
     *
     * @param  mixed  $id
     * @param  mixed  $columns
     */
    public function find($id, $columns = ['*']);

    /**
     * Query option.
     *
     * @param  mixed  $options
     * @param  mixed  $order
     */
    public function query($options = [], $order = []);

    /**
     * Get by option.
     *
     * @param  mixed  $options
     * @param  mixed  $columns
     */
    public function get($options = [], $columns = ['*']);

    /**
     * Get first.
     *
     * @param  mixed  $options
     * @param  mixed  $columns
     */
    public function first($options = [], $columns = ['*']);

    /**
     * Order data.
     *
     * @param  mixed  $order
     * @param  null|mixed  $query
     */
    public function order($order = [], $query = null);

    /**
     * Paginate data.
     *
     * @param  mixed  $options
     * @param  mixed  $page
     * @param  mixed  $limit
     * @param  mixed  $order
     */
    public function paginate($options, $page = 1, $limit = 20, $order = []);

    /**
     * Query option.
     *
     * @param  mixed  $options
     * @param  null|mixed  $query
     */
    public function queryOptions($options = [], $query = null);

    /**
     * Query paginate.
     *
     * @param  mixed  $query
     */
    public function queryPaginate($query, int $page = 1, int $limit = 20);

    /**
     * Paginate query.
     *
     * @param  mixed  $query
     * @param  mixed  $page
     * @param  mixed  $limit
     */
    public function paginatedQuery($query, $page = 1, $limit = 20);
}
