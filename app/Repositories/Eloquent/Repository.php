<?php

namespace App\Repositories\Eloquent;

use App\Repositories\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var integer
     */
    protected $perPage = 20;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get All
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($params = [])
    {
        return $this->model->all();
    }

    /**
     * Get one
     * @param integer $id
     * @param array $filter
     * @return mixed
     */
    public function find($id, $filter = [])
    {
        $result = $this->model->where($filter)->find($id);

        return $result;
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Insert multiple records at the same time
     * @param array $data
     * @return mixed
     */
    public function insertBulk(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * Save record data
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save($record)
    {
        return $record->save();
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }
}
