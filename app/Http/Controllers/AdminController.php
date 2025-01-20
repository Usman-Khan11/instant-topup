<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data['page_title'] = "Dashboard";
        return view('admin.dashboard', $data);
    }
}
