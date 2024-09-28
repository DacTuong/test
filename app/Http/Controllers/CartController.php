<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupons;
use Illuminate\Support\Facades\Redirect;

session_start();
class CartController extends Controller
{

    public function index()
    {
        $shipping_fee = 25000;
        $brand = Brand::get();
        $category = Category::get();
        // Truyền dữ liệu vào view bằng mảng
        Session::put('fee_ship', $shipping_fee);
        return view('user.shopping.cart')->with('brands', $brand)->with('categorys', $category);
    }

    public function addToCart(Request $request)
    {
        $productData = $request->all();
        $product_name = $productData['cart_product_name'];
        $product_price = $productData['cart_product_price'];
        $id_product = $productData['cart_product_id'];
        $product_image = $productData['cart_product_image'];

        $session_id = substr(md5(microtime()), rand(0, 26), 5);
        $cart = Session::get('cart');

        $soluong = 1;
        $is_vaiable = false;

        if (!empty($cart)) {
            foreach ($cart as $key => $val) {
                if ($val['masp'] == $id_product) {
                    $is_vaiable = true;
                    $new_qty = $cart[$key]['soluong'] += $soluong;
                    $cart[$key]['soluong'] = $new_qty;
                    $cart[$key]['total'] = $cart[$key]['soluong'] * $cart[$key]['gia'];
                    break;
                }
            }
        }

        if (!$is_vaiable) {
            $cart[] = array(
                'session_id' => $session_id,
                'masp' => $id_product,
                'image' => $product_image,
                'soluong' => $soluong,
                'tensp' => $product_name,
                'gia' => $product_price,
                'total' => $soluong * $product_price,
            );
        }

        // Tính toán total_price
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['total'];
        }
        Session::put('cart', $cart);
        Session::put('total_price', $total_price);
        Session::save();
    }


    public function count_cart()
    {
        $cartQuantity = Session::get('cart');

        // Hoặc bạn có thể sử dụng model để lấy dữ liệu
        $quantity = 0;
        foreach ($cartQuantity as $item) {
            $quantity += $item['soluong'];
        }

        $output = '';
        $output .= '
            <span id="quantity-cart">
            ' . $quantity . '
            </span>
        ';
        echo $output;
    }

    public function increaseProduct($product_id)
    {
        $cart = Session::get('cart');

        $soluong = 1;
        if ($cart == true) {
            foreach ($cart as $key => $val) {
                if ($val['masp'] == $product_id) {
                    $cart[$key]['soluong'] += $soluong;

                    $cart[$key]['total'] = $cart[$key]['soluong'] * $cart[$key]['gia'];
                    break;
                }
            }
        }
        Session::put('cart', $cart);

        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['total'];
        }
        Session::put('total_price', $total_price);
        Session::save();
        return Redirect::to('cart');
    }

    public function decreaseProduct($product_id)
    {
        $cart = Session::get('cart');
        $soluong = 1;
        if ($cart == true) {
            foreach ($cart as $key => $val) {
                if ($val['masp'] == $product_id) {
                    $new_qty = $cart[$key]['soluong'] -= $soluong;
                    if ($new_qty < 1) {
                        $new_qty = 1;
                        return redirect()->back()->with('message', 'You can add min than 1 of this product to the cart');
                    }
                    $cart[$key]['soluong'] = $new_qty;

                    $cart[$key]['total'] = $cart[$key]['soluong'] * $cart[$key]['gia'];
                    break;
                }
            }
        }
        Session::put('cart', $cart);
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['total'];
        }
        Session::put('total_price', $total_price);

        Session::save();

        return Redirect::to('cart');
    }

    public function delete($session_id)
    {
        $cart = Session::get('cart');

        if ($cart == true) {
            foreach ($cart as $key => $value) {

                if ($value['session_id'] == $session_id) {
                    unset($cart[$key]);
                }
            }
            Session::put('cart', $cart);
            $total_price = 0;
            foreach ($cart as $item) {
                $total_price += $item['total'];
            }
            Session::put('total_price', $total_price);
        }
        return redirect()->back()->with('message', 'You can remove than 1 of this product to the cart');
    }

    public function delete_all_cart()
    {
        $cart = Session::get('cart');
        if ($cart == true) {
            Session::forget('cart');
            Session::forget('coupon');
            Session::forget('total_price');
        }
        return Redirect::to('cart');
    }

    public function check_coupon(Request $request)
    {
        $data = $request->all();
        $id_user = Session::get('id_customer');

        $coupon = Coupons::where('coupon_code', $data['code_coupon'])->first();
        if ($id_user) {
            if ($coupon) {
                $count_coupon = $coupon->count();
                if ($count_coupon > 0) {
                    $coupon_session = Session::get('coupon');
                    if ($coupon_session == true) {
                        $is_avaiabel = 0;
                        if ($is_avaiabel == 0) {
                            $cou[] = array(
                                'coupon_code' => $coupon->coupon_code,
                                'coupon_type' => $coupon->coupon_type,
                                'discount' => $coupon->discount,
                            );
                            Session::put('coupon', $cou);
                        }
                    } else {
                        $cou[] = array(
                            'coupon_code' => $coupon->coupon_code,
                            'coupon_type' => $coupon->coupon_type,
                            'discount' => $coupon->discount,
                        );
                        Session::put('coupon', $cou);
                    }
                    Session::save();

                    $extist_id = explode(',', $coupon->customer_id);
                    if (in_array($id_user, $extist_id)) {
                        echo 'Bạn đã sử dụng mã giảm giá này rồi';
                    } else {
                        if ($coupon->customer_id) {
                            $coupon->customer_id .= ',' . $id_user;
                        } else {
                            $coupon->customer_id = $id_user;
                        }
                        $coupon->coupon_qty = $coupon->coupon_qty - 1;
                        // Session::forget('final_total');
                        $coupon->save();
                        echo 'Dùng mã giảm giá thành công';
                    }
                }
            } else {
                echo 'Mã này không đúng';
            }
        } else {
            echo 'Bạn chưa đăng nhập';
        }
        return Redirect::to('cart');
    }

    public function delete_coupon()
    {
        $coupon = Session::get('coupon');
        if ($coupon == true) {
            Session::forget('coupon');
        }
        return Redirect::to('cart');
    }
}
