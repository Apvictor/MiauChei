<?php

namespace App\Http\Controllers\App;

use App\Helpers\DifferentDates;
use App\Http\Controllers\Controller;
use App\Models\Pets;
use App\Models\Sighted;
use Carbon\Carbon;
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
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/pets-details/{id}",
     *      summary="Detalhes do pet",
     *      description="Retorna detalhes do pet conforme id passado",
     *      security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="ID do pet",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     * )
     */
    public function petsDetails(int $id)
    {
        $dates_differents = new DifferentDates();

        $pets = Pets::findOrFail($id);

        $pets->sighted = Sighted::select('last_seen', 'data_sighted', 'user_pet')->where('pet_id', $pets->id)->latest()->first();
        $pets->times = $dates_differents->dateFormat($pets->created_at);
        $pets->user = Auth::user();

        return response()->json($pets);
    }

    /**
     * @OA\Post(
     *      tags={"Pets"},
     *      path="/pets-store",
     *      summary="Cadastrar pet",
     *      description="Retorna dados do pet",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="sex", type="string"),
     *                 @OA\Property(property="species", type="string"),
     *                 @OA\Property(property="breed", type="string"),
     *                 @OA\Property(property="size", type="string"),
     *                 @OA\Property(property="predominant_color", type="string"),
     *                 @OA\Property(property="secondary_color", type="string"),
     *                 @OA\Property(property="date_disappearance", type="string"),
     *                 @OA\Property(property="last_seen", type="string"),
     *                 @OA\Property(property="photo", type="string"),
     *                 example={
     *                      "name": "Thor",
     *                      "sex": "M",
     *                      "species": "Cachorro",
     *                      "breed": "Vira Lata",
     *                      "size": "Grande",
     *                      "predominant_color": "Preto",
     *                      "secondary_color": "Branco",
     *                      "date_disappearance": "05/04/2022",
     *                      "last_seen": "Rua teste",
     *                      "photo": "teste123"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     */
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
            return response()->json(['message' => $validator->messages()->all()], 400);
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

        $date = new \DateTime($dados['date_disappearance']);
        $data_format = $date->format('Y-m-d');

        $pet = new Pets();
        $pet->name = $dados['name'];
        $pet->species = $dados['species'];
        $pet->sex = $dados['sex'];
        $pet->breed = $dados['breed'];
        $pet->size = $dados['size'];
        $pet->predominant_color = $dados['predominant_color'];
        $pet->secondary_color = $dados['secondary_color'] ?? null;
        $pet->physical_details = $dados['physical_details'] ?? null;
        $pet->date_disappearance = $data_format;
        $pet->photo = $dados['photo'];
        $pet->uuid = $dados['uuid'];
        $pet->user_id = Auth::user()->id;
        $pet->status_id = 1;

        $pet->save();

        $sighted = new Sighted();
        $sighted->last_seen = $dados['last_seen'];
        $sighted->data_sighted = $data_format;
        $sighted->user_id = Auth::user()->id;

        $pet->sighted()->save($sighted);

        return response()->json([
            'message' => 'Cadastro efetuado com sucesso!',
            'pet' => $pet
        ]);
    }

    /**
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/pet-sightings/{id}",
     *      summary="Avistamentos do pet",
     *      description="Retorna os avistamentos do pet conforme id passado",
     *      security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="ID do pet",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     */
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

    /**
     * @OA\Post(
     *      tags={"Pets"},
     *      path="/pets-sighted-store",
     *      summary="Cadastrar avistamentos",
     *      description="Retorna dados do avistamento",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="data_sighted", type="string"),
     *                 @OA\Property(property="pet_id", type="string"),
     *                 @OA\Property(property="last_seen", type="string"),
     *                 @OA\Property(property="user_pet", type="boolean"),
     *                 example={"data_sighted": "05/04/2022", "pet_id": "345", "last_seen": "Rua teste", "user_pet": true}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     */
    public function petsSightedStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_sighted' => ['required'],
            'pet_id' => ['required'],
            'last_seen' => ['required'],
            'user_pet' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()->all()], 400);
        } else {
            $data = $request->all();
            $date = new \DateTime($data['data_sighted']);
            $data_format = $date->format('Y-m-d');

            $sighted = Sighted::create([
                'user_id' => Auth::user()->id,
                'pet_id' => $data['pet_id'],
                'data_sighted' => $data_format,
                'last_seen' => $data['last_seen'],
                'user_pet' => $data['user_pet']
            ]);

            $pet = Pets::findOrFail($data['pet_id']);
            $pet->status_id = 3;
            $pet->save();

            return response()->json([
                'message' => 'Cadastro efetuado com sucesso!',
                'sighted' => $sighted,
                'pet' => $pet
            ]);
        }
    }

    /**
     * @OA\Put(
     *      tags={"Pets"},
     *      path="/pet-found/{id}",
     *      summary="Pet encontrado",
     *      description="Atualização do pet para encontrado",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          description="ID do pet",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     * )
     */
    public function petFound($id)
    {
        $pet = Pets::findOrFail($id);
        $pet->status_id = 2;
        $pet->date_disappearance = Carbon::now()->format('Y-m-d');
        $pet->save();

        return response()->json([
            'message' => 'Atualização efetuada com sucesso!',
            'pet' => $pet
        ]);
    }
}
