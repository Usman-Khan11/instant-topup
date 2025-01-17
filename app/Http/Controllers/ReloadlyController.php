<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReloadlyService;

class ReloadlyController extends Controller
{
    protected $reloadlyService;

    public function __construct(ReloadlyService $reloadlyService)
    {
        $this->reloadlyService = $reloadlyService;
    }
}
