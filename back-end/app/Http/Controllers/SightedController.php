<?php

namespace App\Http\Controllers;

use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SightedController extends Controller
{

    public function index()
    {
        $pets = Pets::join('users', 'pets.user_id', '=', 'users.id')
            ->join('status', 'pets.status_id', '=', 'status.id')
            ->select(
                'users.name as dono',
                'pets.*',
                'status.name as status',
            )
            ->orderBy('date_disappearance', 'DESC')
            ->get();

        $lists = [];
        $list = [];
        for ($i = 0; $i < count($pets); $i++) {
            $lists[$i] = $pets[$i];
            $lists[$i]['count'] = Sighted::where('pet_id', $lists[$i]->id)->count();

            if ($lists[$i]['count'] > 0) {
                $list[$i] = $lists[$i];
            }
        }

        return view('admin.pages.sighted.index', compact('list'));
    }

    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['user_id'] = Auth::user()->id;
        $dados['pet_id'] = intval($request->pet_id);

        $pets = Pets::findOrFail($dados['pet_id']);
        $pets->status_id = 3;
        $pets->created_at = date('Y-m-d H:i:s');
        $pets->save();

        Sighted::create($dados);

        return redirect()->route('pets.lost.index');
    }

    public function show($id)
    {
        $pet = Pets::findOrFail($id);

        $sighted = Sighted::join('pets', 'sighted.pet_id', '=', 'pets.id')
            ->join('users', 'sighted.user_id', '=', 'users.id')
            ->where('pets.id', $id)
            ->select(
                'pets.*',
                'users.name as userAvistou',
                'sighted.last_seen as localAvistamento',
                'sighted.data_sighted as dataAvistamento',
            )
            ->get();

        return view('admin.pages.sighted.show', compact('pet', 'sighted'));
    }
}
