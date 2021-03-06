<?php

namespace App\Http\Controllers;

use App\Models\Breeds;
use App\Models\Pets;
use App\Models\Species;
use App\Models\Status;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $counts = [];
        $counts['users'] = User::all()->count();
        $counts['pets'] = Pets::all()->count();
        $counts['pets_lost'] = Pets::all()->where('status_id', 1)->count();
        $counts['pets_found'] = Pets::all()->where('status_id', 2)->count();
        $counts['species'] = Species::all()->count();
        $counts['breeds'] = Breeds::all()->count();
        $counts['status'] = Status::all()->count();

        return view('admin.pages.home.index', compact('counts'));
    }
}
