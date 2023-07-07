<?php

namespace App\Services;


use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MenuService
{

    public function list()
    {
        try {
            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $userRole = 5;
            } elseif (Auth::user()->role_id == 4) {
                $userRole = 2;
            } elseif (Auth::user()->role_id == 7) {
                $userRole = 8;
            } elseif (Auth::user()->role_id == 5) {
                $userRole = 7;
            }
            return  MenuItem::with('children')->where('menu_id', $userRole)->whereNull('parent_id')->orderBy('order','ASC')->get();
        } catch (\Exception | RequestException $e) {
            Log::error('menu_items_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


}
