<?php

namespace App\Http\Controllers\Shop\Admin;

//use Illuminate\Http\Request;
use App\Http\Requests\AdminCategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Repositories\Admin\CategoryRepository;
use MetaTag;

class CategoryController extends AdminBaseController
{
    private $categoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->categoryRepository = app(CategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrMenu = Category::all();
        $menu = $this->categoryRepository->buildMenu($arrMenu);
        //dd($menu);

        MetaTag::setTags(['title' => 'Список категорий']);
        return view('shop.admin.category.index', ['menu' => $menu]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Category();
        $categoryList = $this->categoryRepository->getComboCategories();

        MetaTag::setTags(['title' => 'Добавление категории']);
        return view('shop.admin.category.create', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminCategoryUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCategoryUpdateRequest $request)
    {
        $name = $this->categoryRepository->checkUniqueName($request->title, $request->parent_id);
        if ($name) {
            return back()->withErrors(['msg' => 'В одной и той же категории не должно быть одинаковых наименований!'])->withInput();
        }
        $data = $request->input();
        $item = new Category();
        $item->fill($data)->save();
        if ($item) {
            return redirect()->route('categories.index')
                ->with(['success' => 'Успешно добавлено!']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка добавления'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CategoryRepository $categoryRepository
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CategoryRepository $categoryRepository)
    {
        $item = $this->categoryRepository->getId($id);
        if (empty($item)) {
            abort(404);
        }

        MetaTag::setTags(['title' => "Категория id [$id]"]);
        return view('shop.admin.category.edit', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminCategoryUpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminCategoryUpdateRequest $request, $id)
    {
        $item = $this->categoryRepository->getId($id);
        if (empty($item)) {
            return back()->withErrors(['msg' => "Категория не найдена"])->withInput();
        }

        $data = $request->all();
        $result = $item->update($data);
        if ($result) {
            return redirect()->route('categories.edit', $item->id)->with(['success' => 'Успешно сохранено!']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    /** @throws \Exception */
    public function mydelete()
    {
        $id = $this->categoryRepository->getRequestID();
        if (!$id) {
            return back()->withErrors(['msg' => 'Ошибка с ID']);
        }

        $children = $this->categoryRepository->checkChildren($id);
        if ($children) {
            return back()->withErrors(['msg' => 'Удаление невозможно - в категории есть вложенные категории!']);
            /* Это сообщение никогда не выведится, потому что в customMenuItems.blade.php прописано условие-заглушка на
            проверку наличия подкатегории*/
        }

        $parents = $this->categoryRepository->checkProductParents($id);
        if ($parents) {
            return back()->withErrors(['msg' => 'Удаление невозможно - данная категория имеет товары!']);
            /* А это сообщение выводится */
        }

        $delete = $this->categoryRepository->deleteCategory($id);
        if ($delete) {
            return redirect()->route('categories.index')
                ->with(['success' => "Категория id [$id] успешно удалена!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
