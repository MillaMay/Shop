<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 24.06.2020
 * Time: 10:07
 */

namespace App\Repositories\Admin;

use App\Models\Admin\Product;
use App\Repositories\CoreRepository;
use App\Models\Admin\Product as Model;

class ProductRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getLastProducts($perpage)
    {
        $get = $this->startConditions()->orderBy('id', 'DESC')->limit($perpage)->paginate($perpage);
        return $get;
    }

    public function getAllProducts($perpage)
    {
        $get_all = $this->startConditions()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.title AS cat')->orderBy('status')
            //->orderBy(\DB::raw('LENGTH(products.title)', 'products.title'))
            ->limit($perpage)->paginate($perpage);

        return $get_all;
    }

    public function getCountProducts()
    {
        $count = $this->startConditions()->count();
        return $count;
    }

    public function getProducts($q)
    {
        $products = \DB::table('products')->select('id', 'title')
            ->where('title', 'LIKE', ["%{$q}%"])->limit(10)->get();

        return$products;
    }

    public function uploadImg($name, $wmax, $hmax)
    {
        $uploaddir = 'uploads/single/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $name));
        $uploadfile = $uploaddir . $name;
        \Session::put('single', $name); // put() for 1 element
        self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
    }

    public function uploadGallery($name, $wmax, $hmax)
    {
        $uploaddir = 'uploads/gallery/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name']));
        $new_name =  time(). ".$ext";
        $uploadfile = $uploaddir . $new_name;
        \Session::push('gallery', $new_name); // push() for more elements
        if (@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
            self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
            $res = array("file" => $new_name);
            echo json_encode($res);
        }
    }

    public function getImg(Product $product)
    {
        clearstatcache(); // Очищает кэш от старых файлов

        if (!empty(\Session::get('single'))) {
            $name = \Session::get('single');
            $product->img = $name;
            \Session::forget('single');
            return;
        }
        if (empty(\Session::get('single')) && !is_file(WWW . '/uploads/single/' . $product->img)) {
            $product->img = null;
        }
        return;
    }

    public function saveGallery($id)
    {
        clearstatcache();

        if (!empty(\Session::get('gallery'))) {
            $sql_part = '';
            foreach (\Session::get('gallery') as $val) {
                $sql_part .= "('$val', $id),";
            }
            $sql_part = rtrim($sql_part, ',');
            \DB::insert("INSERT INTO galleries (img, product_id) VALUES $sql_part");
            \Session::forget('gallery');
            return; // !
        }
    }

    // Скаченный из интернета стандартный метод - обрезает изображение до нужных размеров
    public static function resize($target, $dest, $wmax, $hmax, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $ratio = $w_orig / $h_orig;
        if (($wmax / $hmax) > $ratio) {
            $wmax = $hmax * $ratio;
        } else {
            $hmax = $wmax / $ratio;
        }
        $img = "";
        switch ($ext) {
            case("gif"):
                $img = imagecreatefromgif($target);
                break;
            case("png"):
                $img = imagecreatefrompng($target);
                break;
            default:
                $img = imagecreatefromjpeg($target);
        }
        $newImg = imagecreatetruecolor($wmax, $hmax);
        if ($ext == "png") {
            imagesavealpha($newImg, true);
            $transPng = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
            imagefill($newImg, 0, 0, $transPng);
        }
        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig);
        switch ($ext) {
            case("gif"):
                imagegif($newImg, $dest);
                break;
            case("png"):
                imagepng($newImg, $dest);
                break;
            default:
                imagejpeg($newImg, $dest);
        }
        imagedestroy($newImg);
    }

    public function editFilter($id, $data)
    {
        $filter = \DB::table('attribute_products')
            ->where('product_id', $id)->pluck('attr_id')->toArray();

        // Если фильтры сброшены, а в таблице есть:
        if (empty($data['attrs']) && !empty($filter)) {
            \DB::table('attribute_products')
                ->where('product_id', $id)->delete();
            return;
        }

        // Если фильтры добавляются и в таблице нет:
        if (empty($filter) && !empty($data['attrs'])) {
            $sql_part = '';
            foreach ($data['attrs'] as $val) {
                $sql_part .= "($val, $id),";
            }
            $sql_part = rtrim($sql_part, ',');
            \DB::insert("INSERT INTO attribute_products (attr_id, product_id) VALUES $sql_part");
            return;
        }

        // Если фильтры меняются:
        if (!empty($data['attrs'])) {
            $result = array_diff($filter, $data['attrs']);
            if ($result) {
                \DB::table('attribute_products')
                    ->where('product_id', $id)->delete();
                $sql_part = '';
                foreach ($data['attrs'] as $val) {
                    $sql_part .= "($val, $id),";
                }
                $sql_part = rtrim($sql_part, ',');
                \DB::insert("INSERT INTO attribute_products (attr_id, product_id) VALUES $sql_part");
                return;
            }
        }
    }

    public function editRelatedProduct($id, $data)
    {
        $related_products = \DB::table('related_products')
            ->select('related_id')->where('product_id', $id)
            ->pluck('related_id')->toArray();

        // Если связанные товары не выбраны, но есть в таблице:
        if (empty($data['related']) && !empty($related_products)) {
            \DB::table('related_products')->where('product_id', $id)->delete();
            return;
        }

        // Если связанные товары добавляются и в таблице нет:
        if (empty($related_products) && !empty($data['related'])) {
            $sql_part = '';
            foreach ($data['related'] as $val) {
                $val = (int)$val;
                $sql_part .= "($id, $val),";
            }
            $sql_part = rtrim($sql_part, ',');
            \DB::insert("INSERT INTO related_products (product_id, related_id) VALUES $sql_part");
            return;
        }

        // Если связанные товары меняются:
        if (!empty($data['related'])) {
            $result = array_diff($related_products, $data['related']);
            if (!(empty($result)) || count($related_products) != count($data['related'])) {
                \DB::table('related_products')->where('product_id', $id)->delete();
                $sql_part = '';
                foreach ($data['related'] as $val) {
                    $sql_part .= "($id, $val),";
                }
                $sql_part = rtrim($sql_part, ',');
                \DB::insert("INSERT INTO related_products (product_id, related_id) VALUES $sql_part");
                return; // !
            }
        }
    }

    /** Get all info about one Product */
    public function getInfoProduct($id)
    {
        $product = $this->startConditions()->find($id);

        return $product;
    }

    public function getFilterProduct($id)
    {
        $filter = \DB::table('attribute_products')->select('attr_id')
            ->where('product_id', $id)->pluck('attr_id')->all();

        return $filter;
    }

    public function getRelatedProducts($id)
    {
        $related_products = $this->startConditions()
            ->join('related_products', 'products.id', '=', 'related_products.related_id')
            ->select('products.*', 'related_products.related_id')
            ->where('related_products.product_id', $id)->get();

        return $related_products;
    }

    public function getGallery($id)
    {
        $gallery = \DB::table('galleries')->where('product_id', $id)->pluck('img')->all();
        return $gallery;
    }

    public function returnStatus($id)
    {
        if (isset($id)) {
            $count_prod = \DB::table('products')->where('id', $id)->pluck('count')->first();
            if ($count_prod != 0) { // Проверка количества товара
                $st = \DB::update("UPDATE products SET status = '1' WHERE id = ?", [$id]);
                if ($st) {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    public function deleteStatus($id)
    {
        if (isset($id)) {
            $st = \DB::update("UPDATE products SET status = '0' WHERE id = ?", [$id]);
            if ($st){
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteSingleAndGalleryFromPath($id)
    {
        $galleryImg = \DB::table('galleries')
            ->select('img')->where('product_id', $id)->pluck('img')->all();
        $singleImg = \DB::table('products')
            ->select('img')->where('id', $id)->pluck('img')->all();
        if (!empty($galleryImg)) {
            foreach ($galleryImg as $img) {
                @unlink("uploads/gallery/$img");
            }
        }
        if (!empty($singleImg)) {
            @unlink("uploads/single/" . $singleImg[0]);
        }
    }

    /** Delete one Product from DB */
    public function deleteFromDB($id)
    {
        if (isset($id)) {
            $related_prod = \DB::delete('DELETE FROM related_products WHERE product_id = ?', [$id]);
            $attr_prod = \DB::delete('DELETE FROM attribute_products WHERE product_id = ?', [$id]);
            $gallery_prod = \DB::delete('DELETE FROM galleries WHERE product_id = ?', [$id]);
            $product = \DB::delete('DELETE FROM products WHERE id = ?', [$id]);
            if ($product) {
                return true;
            }
        }
    }
}