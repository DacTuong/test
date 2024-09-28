<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\SalesProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function order_view()
    {
        $ls_dataOrder = OrderProduct::with(['shippingAddress'])->get();

        return view('admin.order.order_view')->with('lsOrder', $ls_dataOrder);
    }
    public function view_detail($order_code)
    {

        $order_count_quantity = 0;
        $data_detailOrder = OrderDetail::where('order_code', $order_code)->get();

        foreach ($data_detailOrder as $detailOrder) {
            $order_count_quantity += $detailOrder['product_sale_quantity'];
        }

        $order_ship = OrderProduct::with(['shippingAddress'])->where('order_code', $order_code)->first();
        return view('admin.order.order_detail')->with('detailOrder', $data_detailOrder)->with('orderShip', $order_ship)->with('orderCount', $order_count_quantity);
    }

    public function accept($order_code)
    {
        $change_status = OrderProduct::find($order_code);

        $data_detailOrder = OrderDetail::where('order_code', $order_code)->get();
        foreach ($data_detailOrder as $detail) {
            $product_id = $detail->product_id;
            $dataProduct = Product::find($product_id);
            if ($dataProduct->product_quantity == 0) {
                echo 'Không thể xác nhận';
            }
            // echo ' xác nhận';
            $change_status->order_status = 2;
            $change_status->save();

            $dataProduct->product_quantity = $dataProduct->product_quantity - 1;
            $dataProduct->save();

            $sale = new SalesProduct();  // Sử dụng mô hình Sale
            $sale->id_product = $product_id;
            $sale->quantity_saled = $detail->product_sale_quantity;
            $sale->name_product = $dataProduct->product_name;
            $sale->save();
        }


        return Redirect::to('order-view');
    }

    public function not_accept($order_code)
    {
        $change_status = OrderProduct::find($order_code);
        $change_status->order_status = 0;
        $change_status->save();
        return Redirect::to('order-view');
    }
}
