<?php

namespace App\Http\Controllers\Shop\Admin;

use App\CShop\Core\ShopApp; // !

use App\Http\Requests\AdminProductCreateRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Repositories\Admin\ProductRepository;
use Illuminate\Http\Request;
use MetaTag;

class ProductController extends AdminBaseController
{
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 10;
        $getAllProducts = $this->productRepository->getAllProducts($perpage);
        $count = $this->productRepository->getCountProducts();


        MetaTag::setTags(['title' => 'Список товаров']);
        return view('shop.admin.product.index', compact('getAllProducts', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Category();

        MetaTag::setTags(['title' => 'Добавление товара']);
        return view('shop.admin.product.create', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminProductCreateRequest  $request
     * @return $this
     */
    public function store(AdminProductCreateRequest $request)
    {
        $data = $request->input();
        $product = (new Product())->create($data);
        $id = $product->id;
        $product->status = $request->count == 0 || !$request->status ? '0' : '1';
        $product->hit = $request->hit ? '1' : '0';
        $product->category_id = $request->parent_id ?? '0';
        $this->productRepository->getImg($product);
        $save = $product->save();
        if ($save) {
            $this->productRepository->editFilter($id, $data);
            $this->productRepository->editRelatedProduct($id, $data);
            $this->productRepository->saveGallery($id);

            return redirect()
                ->route('products.index')
                ->with(['success' => 'Успешно добавлено!']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка добавления'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->getInfoProduct($id);
        if (empty($product)) {
            abort(404);
        }
        ShopApp::get_instance()->setProperty('parent_id', $product->category_id);
        $filters = $this->productRepository->getFilterProduct($id);
        $related_products = $this->productRepository->getRelatedProducts($id);
        $gallery = $this->productRepository->getGallery($id);

        MetaTag::setTags(['title' => "Товар № $id"]);
        return view('shop.admin.product.edit', compact('product', 'filters',
            'related_products', 'gallery'), [
            'categories' => Category::with('children')
                ->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminProductCreateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminProductCreateRequest $request, $id)
    {
        $product = $this->productRepository->getId($id);
        if (empty($product)) {
            return back()->withErrors(['msg' => "Товар не найден"]);
        }
        $data = $request->all();
        $result = $product->update($data);

        if ($request->count == 0 || !$request->status) {
            $product->status = '0';
        }
        if ($request->count > 0 || !$request->status) {
            $product->status = '1';
        }

        $product->hit = $request->hit ? '1' : '0';
        $product->category_id = $request->parent_id ?? $product->category_id;
        $this->productRepository->getImg($product);
        $save = $product->save();

        if ($result && $save) {
            $this->productRepository->editFilter($id, $data);
            $this->productRepository->editRelatedProduct($id, $data);
            $this->productRepository->saveGallery($id);
            return redirect()
                ->route('products.edit', [$product->id])
                ->with(['success' => 'Успешно сохранено!']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    public function ajaxImage(Request $request) // TSingletone, config/params.php
    {
        if ($request->isMethod('get')) {
            return view('shop.admin.product.include.image_single_edit');
        } else {
            $validator = \Validator::make($request->all(),
                [
                    'file' => 'image|mimes:jpeg,png,gif,svg|max:5000',
                ],
                [
                    'file.mimes' => 'Файл должен иметь расширение: .jpg, .jpeg, .png, .gif или .svg',
                    'file.max' => 'Ошибка! Максимальный размер изображения - 5 мб!',
                ]);
            if ($validator->fails()) {
                return array (
                    'fail' => true,
                    'errors' => $validator->errors(),
                );
            }
            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/single/';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);
            $wmax = ShopApp::get_instance()->getProperty('img_width');
            $hmax = ShopApp::get_instance()->getProperty('img_height');

            $this->productRepository->uploadImg($filename, $wmax, $hmax);
            return $filename;
        }
    }

    public function deleteImage($filename)
    {
        \File::delete('uploads/single/' . $filename);
    }

    public function gallery(Request $request) // TSingletone, config/params.php
    {
        $validator = \Validator::make($request->all(),
            [
                'file' => 'image|mimes:jpeg,png,gif,svg|max:5000',
            ],
            [
                'file.mimes' => 'Файл должен иметь расширение: .jpg, .jpeg, .png, .gif или .svg',
                'file.max' => 'Ошибка! Максимальный размер изображения - 5 мб!',
            ]);
        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->errors(),
            );
        }
        if (isset($_GET['upload'])) {
            $wmax = ShopApp::get_instance()->getProperty('gallery_width');
            $hmax = ShopApp::get_instance()->getProperty('gallery_height');
            $name = $_POST['name'];

            $this->productRepository->uploadGallery($name, $wmax, $hmax);
        }
    }

    public function deleteGallery()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $src = isset($_POST['src']) ? $_POST['src'] : null;
        if (!$id || !$src) {
            return;
        }
        if (\DB::delete("DELETE FROM galleries WHERE product_id = ? AND img = ?", [$id, $src])) {
            @unlink("uploads/gallery/$src");
            exit('1');
        }
        return;
    }

    public function related(Request $request)
    {
        $q = isset($request->q) ? htmlspecialchars(trim($request->q)) : '';
        $data['items'] = [];
        $products = $this->productRepository->getProducts($q);
        if ($products) {
            $i = 0;
            foreach ($products as $id => $title) {
                $data['items'][$i]['id'] = $title->id;
                $data['items'][$i]['text'] = $title->title;
                $i++;
            }
        }
        echo json_encode($data);
        die();
    }

    // Изменить status на On
    public function returnstatus($id)
    {
        if ($id) {
            $st = $this->productRepository->returnStatus($id);
            if ($st) {
                return redirect()
                    ->route('products.index')->with(['success' => 'Успешно изменено!']);
            } else {
                return back()->withErrors(['msg' =>
                    'Если количество товара равно нулю, то статус не может быть изменен!']);
                // В репозитории есть проверка количества товара
            }
        }
    }

    // Изменить status на Off
    public function deletestatus($id)
    {
        if ($id) {
            $st = $this->productRepository->deleteStatus($id);
            if ($st) {
                return redirect()
                    ->route('products.index')->with(['success' => 'Успешно изменено!']);
            } else {
                return back()->withErrors(['msg' => 'Ошибка изменения']);
            }
        }
    }

    public function deleteproduct($id)
    {
        if ($id) {
            $gallery_img = $this->productRepository->deleteSingleAndGalleryFromPath($id);
            $db = $this->productRepository->deleteFromDB($id);
            if ($db) {
                return redirect()
                    ->route('products.index')->with(['success' => "Товар № $id успешно удален!"]);
            } else {
                return back()->withErrors(['msg' => 'Ошибка удаления']);
            }
        }
    }
}
