<?php

namespace App\Http\Controllers\App;

use App\Helpers\DifferentDates;
use App\Http\Controllers\Controller;
use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ladumor\OneSignal\OneSignal;

class SightedController extends Controller
{
    private $dates_differents;

    public function __construct(DifferentDates $differentDates)
    {
        $this->dates_differents = $differentDates;
    }

    /**
     * @OA\Get(
     *      tags={"Pets"},
     *      path="/pets-sighted",
     *      summary="Pets avistados",
     *      description="Retorna lista de pets avistados",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     * )
     */
    public function petsSighted(): JsonResponse
    {
        $petsSighted = Pets::join('users', 'pets.user_id', '=', 'users.id')
            ->join('status', 'pets.status_id', '=', 'status.id')
            ->select('users.name as dono', 'pets.*', 'status.name as status',)
            ->where('status_id', 3)
            ->orderBy('date_disappearance', 'DESC')
            ->get();

        $lists = [];
        $list = [];
        for ($i = 0; $i < count($petsSighted); $i++) {
            $lists[$i] = $petsSighted[$i];
            $lists[$i]['count'] = Sighted::where('pet_id', $lists[$i]->id)->count();
            $lists[$i]->sighted = Sighted::select('last_seen', 'data_sighted')->where('pet_id', $petsSighted[$i]->id)->latest()->first();

            $lists[$i]->times = $this->dates_differents->dateFormat($petsSighted[$i]->created_at);
            if ($lists[$i]['count'] > 0) {
                $list[$i] = $lists[$i];
            }
        }

        return response()->json($petsSighted);
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
    public function petSightings(int $id): JsonResponse
    {
        $pet = Pets::select('pets.*')->where('pets.id', $id)->get();

        for ($i = 0; $i < count($pet); $i++) {
            $dateFormat = date_create($pet[$i]->date_disappearance);
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
    public function petsSightedStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'data_sighted' => ['required'],
            'pet_id' => ['required'],
            'last_seen' => ['required'],
            'user_pet' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()->all()], 400);
        }

        $dados = $request->all();

        Sighted::create([
            'user_id' => Auth::user()->id,
            'pet_id' => $dados['pet_id'],
            'data_sighted' => date('Y-d-m', strtotime($dados['data_sighted'])),
            'last_seen' => $dados['last_seen'],
            'user_pet' => $dados['user_pet']
        ]);

        $pet = Pets::findOrFail($dados['pet_id']);
        $pet->status_id = 3;
        $pet->save();

        $devices = DB::table('user_devices')->where('user_id', $pet->user_id)->get();

        $fields['include_player_ids'] = [];
        for ($i = 0; $i < count($devices); $i++) {
            array_push($fields['include_player_ids'], $devices[$i]->os_player_id);
        }

        $message = 'Entre no aplicativo e veja quem avistou o seu Pet!';
        $notification = OneSignal::sendPush($fields, $message);

        return response()->json(['success' => 'Cadastro efetuado com sucesso!', 'notification' => $notification]);
    }
}
