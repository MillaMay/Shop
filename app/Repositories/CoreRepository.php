<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 23.06.2020
 * Time: 18:27
 */

namespace App\Repositories;

use mysql_xdevapi\Exception; // ?

abstract class CoreRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass();

    protected function startConditions()
    {
        return clone $this->model;
    }

    public function getId($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getRequestID($get = true, $id = 'id')
    {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }
        $id = !empty($data[$id]) ? (int)$data[$id] : null;
        if (!$id) {
            throw new \Exception('', 404);
        }
        return $id;
    }
}