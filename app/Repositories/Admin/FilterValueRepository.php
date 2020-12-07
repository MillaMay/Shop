<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 18.07.2020
 * Time: 11:20
 */

namespace App\Repositories\Admin;

use App\Models\Admin\AttributeValue;
use App\Repositories\CoreRepository;
use App\Models\Admin\AttributeValue as Model;
class FilterValueRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCountFilterValuesId($id)
    {
        $count = \DB::table('attribute_values')->where('attr_group_id', $id)->count();
        return $count;
    }

    public function getAllValuesFilter()
    {
        $attrs = \DB::table('attribute_values')
            ->join('attribute_groups', 'attribute_groups.id', '=', 'attribute_values.attr_group_id')
            ->select('attribute_values.*', 'attribute_groups.title')->orderBy('attribute_values.id', 'DESC')
            ->paginate(10);

        return $attrs;
    }

    public function getCountFilters()
    {
        $count = \DB::table('attribute_values')->count();
        return $count;
    }

    public function checkUnique($name)
    {
        $unique = $this->startConditions()->where('value', $name)->count();
        return $unique;
    }

    public function getInfoProduct($id)
    {
        $product = $this->startConditions()->find($id);
        return $product;
    }

    public function deleteValueFilter($id)
    {
        $delete = $this->startConditions()->where('id', $id)->forceDelete();
        return $delete;
    }
}