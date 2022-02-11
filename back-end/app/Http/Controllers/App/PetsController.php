<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Pets;
use Illuminate\Http\Request;

class PetsController extends Controller
{
    public function recent()
    {
        $recentPets = Pets::select('*')
            ->where('status_id', 1)
            ->OrWhere('status_id', 2)
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        if (count($recentPets) <= 0) {
            return response()->json(['message' => 'Sem registros']);
        }

        return response()->json($recentPets);
    }
}
