<?php

namespace App\Http\Controllers\App;

use App\Helpers\DifferentDates;
use App\Http\Controllers\Controller;
use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetsController extends Controller
{
    /**
     * Retorno de pets cadastrados recentemente, tela home
     *
     * @return void
     */
    public function recents()
    {
        $dates_differents = new DifferentDates();

        $recentPets = Pets::select('*')
            ->where('status_id', 1)
            ->OrWhere('status_id', 3)
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        for ($i = 0; $i < count($recentPets); $i++) {
            $recentPets[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $recentPets[$i]->id)->latest()->first();

            $recentPets[$i]->times = $dates_differents->dateFormat($recentPets[$i]->created_at);
        }

        if (count($recentPets) <= 0) {
            return response()->json(['message' => 'Sem registros']);
        }

        return response()->json($recentPets);
    }

    /**
     * Retorno dos pets cadastrados pelo usuário logado, aba meus pets
     *
     * @return void
     */
    public function myPets()
    {
        $user = Auth::user();

        $myPets = Pets::join('users', 'pets.user_id', '=', 'users.id')
            ->join('status', 'pets.status_id', '=', 'status.id')
            ->select('pets.*', 'status.name as status')
            ->where('users.id', $user->id)
            ->orderBy('pets.date_disappearance', 'DESC')
            ->get();


        $result = [];
        for ($i = 0; $i < count($myPets); $i++) {
            $result[$i]['id'] = $myPets[$i]['id'];
            $result[$i]['name'] = $myPets[$i]['name'];
            $dateFormat = date_create($myPets[$i]['date_disappearance']);
            $result[$i]['date_disappearance'] = date_format($dateFormat, 'd/m/Y');
            $result[$i]['photo'] = $myPets[$i]['photo'];
            $result[$i]['status'] = $myPets[$i]['status'];
        }

        return response()->json($result);
    }

    /**
     * Retorna lista de pets perdidos
     *
     * @return void
     */
    public function petsLost()
    {
        $dates_differents = new DifferentDates();

        $petsLost = Pets::select('*')
            ->where('status_id', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        for ($i = 0; $i < count($petsLost); $i++) {
            $petsLost[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $petsLost[$i]->id)->latest()->first();

            $petsLost[$i]->times = $dates_differents->dateFormat($petsLost[$i]->created_at);
        }

        if (count($petsLost) <= 0) {
            return response()->json(['message' => 'Sem registros']);
        }

        return response()->json($petsLost);
    }

    /**
     * Retorna lista de pets avistados
     *
     * @return void
     */
    public function petsSighted()
    {
        $dates_differents = new DifferentDates();

        $petsSighted = Pets::join('users', 'pets.user_id', '=', 'users.id')
            ->join('status', 'pets.status_id', '=', 'status.id')
            ->select(
                'users.name as dono',
                'pets.*',
                'status.name as status',
            )
            ->where('status_id', 3)
            ->orderBy('date_disappearance', 'DESC')
            ->get();

        $lists = [];
        $list = [];
        for ($i = 0; $i < count($petsSighted); $i++) {
            $lists[$i] = $petsSighted[$i];
            $lists[$i]['count'] = Sighted::where('pet_id', $lists[$i]->id)->count();
            $lists[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $petsSighted[$i]->id)->latest()->first();

            $lists[$i]->times = $dates_differents->dateFormat($petsSighted[$i]->created_at);
            if ($lists[$i]['count'] > 0) {
                $list[$i] = $lists[$i];
            }
        }

        return response()->json($petsSighted);
    }

    /**
     * Retorno detalhes do pet conforme ID passado
     *
     * @param integer $id
     * @return void
     */
    public function petsDetails(int $id)
    {
        $dates_differents = new DifferentDates();

        $pets = Pets::findOrFail($id);

        $pets->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $pets->id)->latest()->first();
        $pets->times = $dates_differents->dateFormat($pets->created_at);

        return response()->json($pets);
    }
}
