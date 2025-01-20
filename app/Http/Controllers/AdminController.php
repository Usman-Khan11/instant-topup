<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReloadlyService;

class AdminController extends Controller
{
    protected $reloadlyService;

    public function __construct(ReloadlyService $reloadlyService)
    {
        $this->reloadlyService = $reloadlyService;
    }

    public function dashboard()
    {

        return $this->reloadlyService->getBalance();

        $data['page_title'] = "Dashboard";
        return view('admin.dashboard', $data);
    }
}
