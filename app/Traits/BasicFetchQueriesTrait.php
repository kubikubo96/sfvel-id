<?php

namespace App\Traits;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

/**
 * BasicFetchQueriesTrait.
 *
 * Trait này cung cấp các phương thức truy vấn cơ bản để lấy dữ liệu từ cơ sở dữ liệu,
 * như findOneById, getByIds, applyDatetimeFilter,...
 *
 * Lưu ý khi sử dụng và cập nhật trait này, tuyệt đối:
 * - Không thêm các chức năng ghi vào cơ sở dữ liệu (insert, update, delete) vào trait này.
 * - Không thêm các phương thức truy vấn đọc dữ liệu phức tạp có nhiều điều kiện vào trait này.
 */
trait BasicFetchQueriesTrait
{
    /**
     * Khởi tạo model trong lớp sử dụng trait này, sử dụng kiểu tham chiếu.
     * Ví dụ: $this->model = &$this->sgwHrOrder.
     *
     * @var Builder&Model
     */
    protected $model;

    /**
     * Khởi tạo model nếu chưa được khởi tạo.
     * Mặc định tham chiếu đến thuộc tính đầu tiên là instanceof Model.
     *
     * @return (Builder&Model)
     */
    protected function initModel()
    {
        if (empty($this->model)) {
            $reflectionClass = new \ReflectionClass($this);

            foreach ($reflectionClass->getProperties() as $property) {
                if ($this->{$property->name} instanceof Model) {
                    $this->model = &$this->{$property->name};
                    break;
                }
            }
        }

        return $this->model;
    }

    /**
     * Các toán tử wrap cho datetime filter.
     *
     * @var array|string[]
     */
    public static array $WRAP_OPERATORS = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
    ];

    public function findOne(int|string $id, array $columns = ['*']): ?Model
    {
        $this->model = $this->initModel();

        return $this->model->select($columns)->find($id);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOneOrFail(int|string $id, array $columns = ['*']): ?Model
    {
        $this->model = $this->initModel();

        return $this->model->select($columns)->findOrFail($id);
    }

    public function findBy(string $column, $value, array $columns = ['*']): ?Model
    {
        $this->model = $this->initModel();

        $query = $this->model->select($columns);

        return $query->where($column, $value)->first();
    }

    public function getByIds(array $ids, array $columns = ['*']): ?Collection
    {
        $this->model = $this->initModel();

        return $this->model->select($columns)->whereKey($ids)->get();
    }

    public function getBy(string $column, $values, array $columns = ['*']): ?Collection
    {
        $this->model = $this->initModel();

        $query = $this->model->select($columns);

        if (is_array($values)) {
            $query->whereIn($column, $values);
        } else {
            $query->where($column, $values);
        }

        return $query->get();
    }

    /**
     * Apply datetime filters to query.
     */
    protected function applyDatetimeFilter(Builder $query, string $column, array|string $values): ?Builder
    {
        // If the $values parameter is a string, convert it to an array with default operator ("=")
        $values = is_string($values) ? ['=' => $values] : $values;

        foreach ($values as $operator => $value) {
            // Wrap the operator if it is present in the $WRAP_OPERATORS array
            $operator = self::$WRAP_OPERATORS[$operator] ?? $operator;

            // Parse the value as a datetime using Carbon
            $datetime = Carbon::parse($value);

            // Check if the value is a valid date format
            $isDate = $datetime->format(config('config.date_format')) === $value;

            // Check if the value is a valid datetime format
            $isDatetime = $datetime->format(config('config.datetime_format')) === $value;

            // Apply a whereDate filter if the value is a valid date format
            if ($isDate) {
                $query->whereDate($column, $operator, $value);
            }

            // Apply a regular where filter if the value is a valid datetime format
            if ($isDatetime) {
                $query->where($column, $operator, $datetime);
            }

            // Throw an exception if the value is not a valid datetime format
            if (! $isDate && ! $isDatetime) {
                throw new InvalidFormatException("Invalid datetime format: {$value}");
            }
        }

        return $query;
    }
}
