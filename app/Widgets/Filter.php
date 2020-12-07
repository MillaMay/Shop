<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class Filter extends AbstractWidget
{
    public $groups;
    public $attrs;
    public $tpl;
    public $filter;
    public $category;
    protected $config = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->tpl = $this->config['tpl'];
        $this->filter = $this->config['filter'];
        $this->category = $this->config['category'];
    }

    public function run()
    {
        $this->groups = $this->getGroups();
        $this->attrs = $this->getAttrs();

        return view($this->tpl, [       // view($this->tpl - это, чтобы возможно было использовать виджет на разных страницах
            'config' => $this->config,
            'groups' => $this->groups,
            'attrs' => $this->attrs,
            'filter' => $this->filter,
            'category' => $this->category,
        ]);
    }
    protected function getAttrs()
    {
        $data = \DB::table('attribute_values')->get();
        $attrs = [];
        foreach ($data as $key => $value) {
            $attrs[$value->attr_group_id][$value->id] = $value->value;
        }
        return $attrs;
    }

    protected function getGroups()
    {
        $data = \DB::table('attribute_groups')->get();
        $groups = [];
        foreach ($data as $key => $value) {
            $groups[$value->id] = $value->title;
        }
        return $groups;
    }

    public function getFilter()
    {
        $filter = null;
    }

    public function getCategory()
    {
        $category = null;
    }
}
