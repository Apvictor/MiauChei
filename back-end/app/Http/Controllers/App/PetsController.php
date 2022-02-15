<?php

namespace App\Http\Controllers\App;

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
        $data_atual = date_create(date('y-m-d'));

        $recentPets = Pets::select('*')
            ->where('status_id', 1)
            ->OrWhere('status_id', 3)
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        for ($i = 0; $i < count($recentPets); $i++) {
            $recentPets[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $recentPets[$i]->id)->latest()->first();

            $date2 = date_create(date_format($recentPets[$i]->created_at, 'y-m-d'));
            $diff = date_diff($data_atual, $date2);

            $year = intval($diff->format("%y"));
            $month = intval($diff->format("%m"));
            $days = intval($diff->format("%d"));
            $result = 0;

            if ($year > 0) {
                $result = [
                    'anos' => $year == 1 ? strval($year . ' ano') : strval($year . ' anos'),
                    'meses' => $month == 1 ? strval($month . ' mês') : strval($month . ' meses')
                ];
            } elseif ($month > 0 && $month <= 12) {
                $result = [
                    'meses' => $month == 1 ? strval($month . ' mês') : strval($month . ' meses'),
                    'dias' => $days == 1 ? strval($days . ' dia') : strval($days . ' dias')
                ];
            } elseif ($days <= 31) {
                $result = ['dias' => $days == 1 ? strval($days . ' dia') : strval($days . ' dias')];
            }

            $recentPets[$i]->times = $result;
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
}
