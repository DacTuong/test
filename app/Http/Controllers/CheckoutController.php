<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Brand;
use App\Models\Coupons;
use App\Models\Feeship;
use App\Models\Category;
use App\Models\Province;
use App\Models\District;
use App\Models\OrderDetail;
use App\Models\OrderProduct;
use App\Models\Ward;



use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;

session_start();

class CheckoutController extends Controller
{
    public function login_index()
    {
        return view('user.account.login');
    }

    public function register_index()
    {
        return view('user.account.register');
    }

    public function logout()
    {
        Session::flush();
        Session::forget('cart');
        Session::forget('coupon');
        return Redirect::to('/');
    }

    public function add_customer(Request $request)
    {
        $data = $request->all();
        $user_add = new User;
        $user_add->name_user = $data['user_name'];
        $user_add->email_user = $data['user_email'];
        $user_add->password_user = $data['user_password'];
        $user_add->status_user = 1;
        $user_add->phone_user = $data['user_phone'];
        $user_add->save();

        $id_user = $user_add->id_user;
        // echo $id_user;

        Session::put('id_customer', $id_user);
        Session::put('name_customer', $user_add->name_user);
        return Redirect::to('/');
    }


    public function login_customer(Request $request)
    {
        $email_customer = $request->user_email;
        $password_customer = $request->user_password;

        $result = User::where('email_user', $email_customer)->where('password_user', $password_customer)->first();

        if ($result) {
            Session::put('id_customer', $result->id_user);
            Session::put('name_customer', $result->name_user);
            return Redirect::to('/');
        } else {
            Session::put('message', 'Sai mật khẩu hoặc tài khoản,vui lòng nhập lại');
            return view('user.account.login');
        }
    }

    public function checkout()
    {
        $province = Province::all();
        $brand = Brand::get();
        $category = Category::get();

        return view('user.shopping.checkout')->with('provinces', $province)->with('brands', $brand)->with('categorys', $category);
    }


    public function select_district_shipping(Request $request)
    {
        $data = $request->all();
        $id_city = $data['id_city'];
        $districts = District::where('matp', $id_city)->get();
        $output = '<option value="">Chọn Quận/Huyện</option>';
        foreach ($districts as $district) {
            $output .= '<option value="' . $district->maqh . '">' . $district->name . '</option>';
        }
        echo $output;
    }

    public function select_wards_shipping(Request $request)
    {
        $data = $request->all();
        $districtID = $data['id_district'];
        $wards = Ward::where('maqh', $districtID)->get();
        $output = '<option value="">Chọn Xã/Phường</option>';
        foreach ($wards as $ward) {
            $output .= '<option value="' . $ward->xaid . '">' . $ward->name . '</option>';
        }
        echo $output;
    }

    public function get_feeship(Request $request)
    {

        $data = $request->all();
        $city_id = $data['id_city'];
        $district_id = $data['id_district'];
        $ward_id = $data['id_ward'];

        $get_feeship = Feeship::where('matp', $city_id)
            ->where('maqh', $district_id)
            ->where('xaid', $ward_id)
            ->first();


        $formatted_feeship = number_format($get_feeship->feeship, 0, ',', '.');
        return response()->json(['feeship' => $formatted_feeship]);
        // $output = '';


        // Nếu tìm thấy phí ship, hiển thị kết quả

        // $output = number_format($get_feeship->feeship, 0, ',', '.');

        // Xuất kết quả
        // echo $output;
    }



    public function order_product(Request $request)
    {

        // $variable_Cart = Session::get('cart');
        $id_user = Session::get('id_customer');

        $data = $request->all();


        $nameorder = $data['fullname'];
        $phonenumber = $data['phonenumber'];
        $city = $data['city'];
        $district = $data['district'];
        $wards = $data['wards'];
        $address = $data['address'];

        $shipping_address = new ShippingAddress();
        $shipping_address->id_customer = $id_user;
        $shipping_address->fullname = $nameorder;
        $shipping_address->order_phone = $phonenumber;
        $shipping_address->matp = $city;
        $shipping_address->maqh = $district;
        $shipping_address->xaid = $wards;
        $shipping_address->diachi = $address;
        $shipping_address->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Đơn hàng đã được gửi thành công!'
        ]);
    }
}