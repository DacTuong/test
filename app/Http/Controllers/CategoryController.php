<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Category;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;

session_start();
class CategoryController extends Controller
{

    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admincp')->send();
        }
    }
    public function add_category_product()
    {
        $this->AuthLogin();
        return view('admin.add_categories_product');
    }
    public function list_category_product()
    {
        $this->AuthLogin();
        $list_category_product = Category::all();
        $manager_category_product = view('admin.list_category_product')->with('list_category_product', $list_category_product);
        return view('admin_layout')->with('admin.list_category_product', $manager_category_product);
    }
    public function save_category_product(Request $request)
    {
        $this->AuthLogin();
        $data = $request->all();
        $categories = new Category;
        $categories->category_name = $data['categories_product_name'];
        $categories->category_status = $data['categories_product_status'];
        $categories->save();
        Session::put('message_success', 'Thêm thành công!');
        return Redirect::to('add-category-product');
    }

    public function inactive_category_product($categories_product_id)
    {
        $this->AuthLogin();

        $category = Category::find($categories_product_id);
        $category->category_status = 1;
        $category->save();
        Session::put('message_success', 'Hiển thị thành công!');
        return Redirect::to('list-category-product');
    }
    public function active_category_product($categories_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($categories_product_id);
        $category->category_status = 0;
        $category->save();
        Session::put('message_success', 'Ẩn thành công!');
        return Redirect::to('list-category-product');
    }

    public function edit_category_product($categories_product_id)
    {
        $this->AuthLogin();
        $edit_category = Category::find($categories_product_id);
        $manager_category = view('admin.edit_categories_product')->with('edit_category', $edit_category);
        return view('admin_layout')->with('admin.edit_category', $manager_category);
    }

    public function update_category_product(Request $request, $categories_product_id)
    {
        $this->AuthLogin();
        $update_category = Category::find($categories_product_id);
        $update_category->category_name = $request->categories_product_name;
        $update_category->save();
        Session::put('message_success', 'Cập nhật thành công!');
        return Redirect::to('list-category-product');
    }
    public function delete_category_product($categories_product_id)
    {
        $this->AuthLogin();
        $delete_category = Category::find($categories_product_id);
        $delete_category->delete();
        Session::put('message_success', 'Xóa thành công!');
        return Redirect::to('list-category-product');
    }
}