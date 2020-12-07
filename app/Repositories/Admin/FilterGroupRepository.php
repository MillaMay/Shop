<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 18.07.2020
 * Time: 11:06
 */

namespace App\Repositories\Admin;

use App\Repositories\CoreRepository;
use App\Models\Admin\AttributeGroup as Model;

class FilterGroupRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllGroupsFilter()
    {
        $attr_groups = \DB::table('attribute_groups')->orderBy('attribute_groups.id')->get()->all();
        return $attr_groups;
    }

    public function getInfoProduct($id)
    {
        $product = $this->startConditions()->find($id);
        return $product;
    }

    public function deleteGroupFilter($id)
    {
        $delete = $this->startConditions()->where('id', $id)->forceDelete();
        return $delete;
    }
}