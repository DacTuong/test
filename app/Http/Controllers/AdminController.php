<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;

session_start();
class AdminController extends Controller
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

    public function index()
    {
        return view('admin.admin_login');
    }

    public function show_dashboard()
    {
        $this->AuthLogin();
        return view('admin.dashboard');
    }
    public function login(Request $request)
    {
        $admin_email = $request->admin_email;
        $admin_password = md5($request->admin_password);

        $result = DB::table('tbl_admin')->where('admin_email', $admin_email)->where('admin_password', $admin_password)->first();

        if ($result) {
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            return Redirect::to('/dashboard');
        } else {
            Session::put('message', 'Sai mật khẩu hoặc tài khoản,vui lòng nhập lại');
            return Redirect::to('/admincp');
        }
    }
    public function logout()
    {
        $this->AuthLogin();
        Session::forget('admin_name');
        Session::forget('admin_id');
        return Redirect::to('/admincp');
    }
}