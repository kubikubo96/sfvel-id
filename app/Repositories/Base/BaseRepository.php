<?php

namespace App\Repositories\Base;

use App\Traits\ReadRepositoryTrait;
use App\Traits\WriteRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements IBaseReadRepository, IBaseWriteRepository
{
    use ReadRepositoryTrait, WriteRepositoryTrait {
        ReadRepositoryTrait::find insteadof WriteRepositoryTrait;
    }

    /**
     * @var Builder&Model
     */
    protected $_model;

    /**
     * BaseWriteRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model class.
     */
    abstract public function getModelClass();

    /**
     * Set model.
     */
    public function setModel()
    {
        $this->_model = resolve($this->getModelClass());
    }

    public function getModel()
    {
        return $this->_model;
    }

    public function _model()
    {
        return $this->_model;
    }
}
