<?php

namespace App\Criterias;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;

abstract class BooleanCriteria implements CriteriaInterface
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function createByRequest(Request $request, $field)
    {
        $class = get_called_class();
        return new $class($request->get($field, null));
    }

    /**
     * Apply criteria in query repository.
     *
     * @param $model
     * @param \Prettus\Repository\Contracts\RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        if (is_bool($this->value)) {
            if ($this->value) {
                return $this->trueScope($model, $repository);
            } else {
                return $this->falseScope($model, $repository);
            }
        }

        return $this->defaultScope($model);
    }

    abstract function trueScope($model, \Prettus\Repository\Contracts\RepositoryInterface $repository);

    abstract function falseScope($model, \Prettus\Repository\Contracts\RepositoryInterface $repository);

    function defaultScope($model, \Prettus\Repository\Contracts\RepositoryInterface $repository)
    {
        return $model;
    }
}