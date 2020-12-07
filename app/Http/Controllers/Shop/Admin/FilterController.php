<?php

namespace App\Http\Controllers\Shop\Admin;

use App\Http\Requests\AdminFilterAddGroupRequest;
use App\Http\Requests\AdminFilterAddValueRequest;
use App\Models\Admin\AttributeGroup;
use App\Models\Admin\AttributeValue;
use App\Repositories\Admin\FilterGroupRepository;
use App\Repositories\Admin\FilterValueRepository;
use MetaTag;

class FilterController extends AdminBaseController
{
    private $filterGroupRepository;
    private $filterValueRepository;

    public function __construct()
    {
        parent::__construct();
        $this->filterGroupRepository = app(FilterGroupRepository::class);
        $this->filterValueRepository = app(FilterValueRepository::class);
    }

    public function attributeGroup()
    {
        $attr_groups = $this->filterGroupRepository->getAllGroupsFilter();

        MetaTag::setTags(['title' => 'Список групп фильтров']);
        return view('shop.admin.filter.groups-filter', compact('attr_groups'));
    }

    public function groupAdd(AdminFilterAddGroupRequest $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $group = (new AttributeGroup())->create($data);
            $group->save();
            if ($group) {
                return redirect('/admin/filter/groups-filter')
                    ->with(['success' => 'Успешно добавлено!']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка добавления'])->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                MetaTag::setTags(['title' => 'Добавление группы фильтров']);
                return view('shop.admin.filter.groups-add-group');
            }
        }
    }

    public function groupEdit(AdminFilterAddGroupRequest $request, $id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => "Группа не найдена"]);
        }
        if ($request->isMethod('post')) {
            $group = AttributeGroup::find($id);
            $group->title = $request->title;
            $group->save();
            if ($group) {
                return redirect(url('/admin/filter/group-edit', $id))
                    ->with(['success' => 'Успешно сохранено!']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
               $group = $this->filterGroupRepository->getInfoProduct($id);
                if (empty($group)) {
                    abort(404);
                }

                MetaTag::setTags(['title' => "Группа id [$id]"]);
                return view('shop.admin.filter.group-edit', compact('group'));
            }
        }
    }

    public function groupDelete($id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => "Группа не найдена"]);
        }
        $count = $this->filterValueRepository->getCountFilterValuesId($id);
        if ($count) {
            return back()->withErrors(['msg' => 'Удаление невозможно - данная группа имеет фильтры']);
        }
        $delete = $this->filterGroupRepository->deleteGroupFilter($id);
        if ($delete) {
            return back()->with(['success' => "Группа id [$id] успешно удалена!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }

    public function attributeValue()
    {
        $attrs = $this->filterValueRepository->getAllValuesFilter();
        $count = $this->filterValueRepository->getCountFilters();

        MetaTag::setTags(['title' => 'Список фильтров']);
        return view('shop.admin.filter.values-filter', compact('attrs', 'count'));
    }

    public function valueAdd(AdminFilterAddValueRequest $request)
    {
        if ($request->isMethod('post')) {
            $uniqueName = $this->filterValueRepository->checkUnique($request->value);
            if ($uniqueName) {
                return redirect('/admin/filter/values-add-value')
                    ->withErrors(['msg' => 'Такое название фильтра уже существует'])->withInput();
            }
            $data = $request->input();
            $attr = (new AttributeValue())->create($data);
            $attr->save();
            if ($attr) {
                return redirect('/admin/filter/values-filter')
                    ->with(['success' => 'Успешно добавлено!']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка добавления'])->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                $group = $this->filterGroupRepository->getAllGroupsFilter();

                MetaTag::setTags(['title' => 'Добавление фильтра']);
                return view('shop.admin.filter.values-add-value', compact('group'));
            }
        }
    }

    public function valueEdit(AdminFilterAddValueRequest $request, $id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => 'Фильтр не найден']);
        }
        if ($request->isMethod('post')) {
            $attr = AttributeValue::find($id);
            $attr->value = $request->value;
            $attr->attr_group_id = $request->attr_group_id;
            $attr->save();
            if ($attr) {
                return redirect(url('/admin/filter/value-edit', $id))
                    ->with(['success' => 'Успешно сохранено!']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                $attr = $this->filterValueRepository->getInfoProduct($id);
                if (empty($attr)) {
                    abort(404);
                }
                $group = $this->filterGroupRepository->getAllGroupsFilter();

                MetaTag::setTags(['title' => "Фильтр id [$id]"]);
                return view('shop.admin.filter.value-edit', compact('group', 'attr'));
            }
        }
    }

    public function valueDelete($id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => 'Фильтр не найден']);
        }
        $delete = $this->filterValueRepository->deleteValueFilter($id);
        if ($delete) {
            return back()->with(['success' => "Фильтр id [$id] успешно удален!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
