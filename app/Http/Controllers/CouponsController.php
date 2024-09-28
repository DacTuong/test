<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupons;
use Illuminate\Support\Facades\Redirect;

class CouponsController extends Controller
{
    public function add_discount()
    {
        return view('admin.coupon.discount_code');
    }

    public function save_coupons(Request $request)
    {
        $data_coupon = $request->all();
        $coupon = new Coupons();
        $coupon->name_coupon = $data_coupon['name_code'];
        $coupon->coupon_code = $data_coupon['discountCode'];
        $coupon->coupon_qty = $data_coupon['qty_code'];
        $coupon->coupon_type = $data_coupon['type_code'];
        $coupon->discount = $data_coupon['discount_amount'];
        $coupon->start_date = $data_coupon['start_date'];
        $coupon->end_date = $data_coupon['end_date'];
        $coupon->save();
        return Redirect::to('/add-discount-code');
    }

    public function list_coupons()
    {
        $coupons = Coupons::get();
        return view('admin.coupon.list_coupons')->with('list_coupon',  $coupons);
    }

    public function delete_coupon($id_coupon)
    {
        $coupon = Coupons::find($id_coupon);
        $coupon->delete();
        return Redirect::to('/list-coupons');
    }
}
