<?php

namespace App\Repositories\Base;

use App\Traits\ReadRepositoryTrait;
use Illuminate\Database\Eloquent\Model;

abstract class BaseReadRepository implements IBaseReadRepository
{
    use ReadRepositoryTrait;

    /**
     * @var Model
     */
    protected $_model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model.
     *
     * @return mixed
     */
    abstract public function getModel();

    /**
     * Set model.
     */
    public function setModel()
    {
        $this->_model = resolve($this->getModel());
    }
}
