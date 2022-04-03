<?php

namespace App\Http\Controllers\App;

use App\Helpers\DifferentDates;
use App\Http\Controllers\Controller;
use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PetsController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/recents",
     *      summary="Pets recente",
     *      description="Retorno de pets cadastrados recentemente",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     *
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
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/mypets",
     *      summary="Meus Pets",
     *      description="Retorno dos pets cadastrados pelo usuário logado",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     *
     */
    public function myPets(): \Illuminate\Http\JsonResponse
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
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/pets-lost",
     *      summary="Pets perdidos",
     *      description="Retorna lista de pets perdidos",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     *
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
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/pets-sighted",
     *      summary="Pets avistados",
     *      description="Retorna lista de pets avistados",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     *
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

    public function petsStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'sex' => ['required'],
            'photo' => ['required'],
            'species' => ['required'],
            'breed' => ['required'],
            'size' => ['required'],
            'predominant_color' => ['required'],
            'date_disappearance' => ['required'],
            'last_seen' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all()[0]], 400);
        } else {
            $dados = $request->all();

            $dados['uuid'] = Str::uuid();

            $data = explode(',', $dados['photo']);
            $folder = 'pets/';
            $name = $folder . $dados['uuid'] . '.jpg';
            Storage::disk('s3')->put($name, base64_decode($data[0]));
            $url = Storage::disk('s3')->url($name);
            $dados['photo'] = $url;
        }

        $pet = new Pets();
        $pet->name = $dados['name'];
        $pet->species = $dados['species'];
        $pet->sex = $dados['sex'];
        $pet->breed = $dados['breed'];
        $pet->size = $dados['size'];
        $pet->predominant_color = $dados['predominant_color'];
        $pet->secondary_color = $dados['secondary_color'] ?? null;
        $pet->physical_details = $dados['physical_details'] ?? null;
        $pet->date_disappearance = $dados['date_disappearance'];
        $pet->photo = $dados['photo'];
        $pet->uuid = $dados['uuid'];
        $pet->user_id = Auth::user()->id;
        $pet->status_id = 1;

        $pet->save();

        $sighted = new Sighted();
        $sighted->last_seen = $dados['last_seen'];
        $sighted->data_sighted = $dados['date_disappearance'];
        $sighted->user_id = Auth::user()->id;

        $pet->sighted()->save($sighted);

        return response()->json([
            'status' => true,
            'message' => 'Cadastro efetuado com sucesso!',
            'pet' => $pet
        ]);
    }

    //Lista de avistamentos do Pet
    public function petSightings(int $id): \Illuminate\Http\JsonResponse
    {
        $pet = Pets::select('pets.*')->where('pets.id', $id)->get();

        for ($i = 0; $i < count($pet); $i++) {
            $dateFormat = date_create($pet[$i]->created_at);
            $pet[$i]->publicado = date_format($dateFormat, 'd/m/Y');
            $pet[$i]->sighted = Sighted::join('users', 'sighted.user_id', '=', 'users.id')->select('sighted.*', 'users.name as dono')->where('sighted.pet_id', $pet[$i]->id)->get();

            for ($y = 0; $y < count($pet[$i]->sighted); $y++) {
                $dateFormat = date_create($pet[$i]->sighted[$y]->data_sighted);
                $pet[$i]->sighted[$y]['data_perdido'] = date_format($dateFormat, 'd/m/Y');
            }
        }

        return response()->json($pet);
    }

    public function petsSightedStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data_sighted' => ['required'],
            'pet_id' => ['required'],
            'last_seen' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all()[0]], 400);
        } else {
            $data = $request->all();

            $sighted = Sighted::create([
                'user_id' => Auth::user()->id,
                'pet_id' => $data['pet_id'],
                'data_sighted' => $data['data_sighted'],
                'last_seen' => $data['last_seen']
            ]);

            $pet = Pets::findOrFail($data['pet_id']);
            $pet->status_id = 3;
            $pet->save();

            return response()->json([
                'status' => true,
                'message' => 'Cadastro efetuado com sucesso!',
                '$sighted' => $sighted,
                '$pet' => $pet
            ]);
        }
    }
}
