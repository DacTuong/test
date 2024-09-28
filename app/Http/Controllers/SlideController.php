<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function new_slide()
    {
        return view('admin.slide.new_slider');
    }
    public function save_slide(Request $request)
    {
        $data = $request->all();
        $save_slide = new Slide();
        $save_slide->name_slide = $data['name_slide'];
        $save_slide->status_slide = 1;

        $get_image = $request->file('slide_image');

        // xữ lý phần up hình ảnh lên mysql
        if ($get_image) {
            $new_image = time() . '_' . $get_image->getClientOriginalName();
            $get_image->move('uploads/slide', $new_image);
            $save_slide->slide_image = $new_image;
        } else {
            $save_slide->slide_image = '';
        }

        $save_slide->save();
    }
}