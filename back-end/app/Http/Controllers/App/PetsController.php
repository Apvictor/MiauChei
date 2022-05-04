<?php

namespace App\Http\Controllers\App;

use App\Helpers\DifferentDates;
use App\Http\Controllers\Controller;
use App\Models\Pets;
use App\Models\Sighted;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PetsController extends Controller
{
    private $dates_differents;

    public function __construct(DifferentDates $differentDates)
    {
        $this->dates_differents = $differentDates;
    }

    /**
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/recents",
     *      summary="Pets recente",
     *      description="Retorno de pets cadastrados recentemente",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200,description="Successful operation",),
     *      @OA\Response(response=400,description="Bad Request"),
     * )
     */
    public function recents(): JsonResponse
    {
        $recentPets = Pets::select('*')
            ->where('status_id', 1)
            ->OrWhere('status_id', 3)
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        for ($i = 0; $i < count($recentPets); $i++) {
            $recentPets[$i]->sighted = Sighted::select('last_seen', 'data_sighted')
                ->where('pet_id', $recentPets[$i]->id)
                ->orderBy('created_at', 'DESC')
                ->first();

            $recentPets[$i]->times = $this->dates_differents->dateFormat($recentPets[$i]->created_at);
        }

        if (count($recentPets) == 0) {
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
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     * )
     */
    public function myPets(): JsonResponse
    {
        $myPets = Pets::join('users', 'pets.user_id', '=', 'users.id')
            ->join('status', 'pets.status_id', '=', 'status.id')
            ->select('pets.*', 'status.name as status')
            ->where('users.id', Auth::user()->id)
            ->orderBy('pets.date_disappearance', 'ASC')
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
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     * )
     */
    public function petsLost(): JsonResponse
    {
        $petsLost = Pets::select('*')
            ->where('status_id', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        for ($i = 0; $i < count($petsLost); $i++) {
            $petsLost[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $petsLost[$i]->id)->latest()->first();
            $petsLost[$i]->times = $this->dates_differents->dateFormat($petsLost[$i]->created_at);
        }

        if (count($petsLost) <= 0) {
            return response()->json(['message' => 'Sem registros']);
        }

        return response()->json($petsLost);
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
    public function petsDetails(int $id): JsonResponse
    {
        $pets = Pets::findOrFail($id);

        $dateFormat = date_create($pets->date_disappearance);

        $pets->date_disappearance = date_format($dateFormat, 'd/m/Y');
        $pets->sighted = Sighted::select('last_seen', 'data_sighted', 'user_pet')
            ->where('pet_id', $pets->id)
            ->orderBy('created_at', 'DESC')
            ->first();
        $pets->times = $this->dates_differents->dateFormat($pets->created_at);
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
     *                 example={"name": "Thor", "sex": "M", "species": "Cachorro", "breed": "Vira Lata", "size": "Grande", "predominant_color": "Preto", "secondary_color": "Branco", "date_disappearance": "05/04/2022", "last_seen": "Rua teste", "photo": "teste123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     * @throws Exception
     */
    public function petsStore(Request $request): JsonResponse
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

        return response()->json(['success' => 'Cadastro efetuado com sucesso!']);
    }
}
