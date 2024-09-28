<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Feeship;
use Illuminate\Http\Request;

class FeeshipController extends Controller
{
    public function feeship(Request $request)
    {
        $provinces = Province::all();

        $feeship_list = Feeship::paginate(10); // 10 là số lượng bản ghi trên mỗi trang

        return view('admin.feeship.feeship_page')->with(compact('provinces', 'feeship_list'));
    }


    public function getDistricts(Request $request)
    {
        $data = $request->all();
        $provinceId = $data['id_province'];
        $districts = District::where('matp', $provinceId)->get();
        $output = '<option value="">Chọn Quận huyện</option>';
        foreach ($districts as $district) {
            $output .= '<option value="' . $district->maqh . '">' . $district->name . '</option>';
        }
        echo $output;
    }

    public function getWards(Request $request)
    {
        $data = $request->all();
        $districtID = $data['id_district'];
        $wards = Ward::where('maqh', $districtID)->get();
        $output = '<option value="">Chọn Quận huyện</option>';
        foreach ($wards as $ward) {
            $output .= '<option value="' . $ward->xaid . '">' . $ward->name . '</option>';
        }
        echo $output;
    }


    public function add_feeship(Request $request)
    {
        $data = $request->all();
        $id_province = $data['id_province'];

        $feeshipOptions = [50000, 55000, 60000, 70000];
        $list_district = District::where('matp', $id_province)->pluck('maqh');

        $count = 0; // Đếm số bản ghi đã tồn tại
        $added = 0; // Đếm số bản ghi mới được thêm
        $exists = false; // Biến kiểm tra xem đã tồn tại hay chưa

        foreach ($list_district as $district_id) {
            $list_wards = Ward::where('maqh', $district_id)->pluck('xaid');

            foreach ($list_wards as $ward_id) {
                $feeshipExists = Feeship::where('matp', $id_province)
                    ->where('maqh', $district_id)
                    ->where('xaid', $ward_id)
                    ->exists();

                if ($feeshipExists) {
                    $exists = true; // Đã tồn tại ít nhất một bản ghi
                    $count++;
                } else {
                    $random_feeship = $feeshipOptions[array_rand($feeshipOptions)];

                    $feeship_add = new Feeship();
                    $feeship_add->matp = $id_province;
                    $feeship_add->maqh = $district_id;
                    $feeship_add->xaid = $ward_id;
                    $feeship_add->feeship = $random_feeship;
                    $feeship_add->save();
                    $added++;
                }
            }
        }

        return response()->json(['exists' => $exists, 'added' => $added, 'count' => $count]);
    }
}
