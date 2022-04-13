<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Usuário"},
     *      path="/profile",
     *      summary="Perfil do Usuário",
     *      description="Retorno do perfil do Usuário",
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     * )
     */
    public function getProfile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json($user);
    }

    /**
     * @OA\Post(
     *      tags={"Usuário"},
     *      path="/profile",
     *      summary="Atualização do Perfil",
     *      description="Retorna dados atualizados",
     *      @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *                 @OA\Property(property="photo", type="string", format="byte"),
     *                 example={"name": "Armando", "phone": "999999999", "password": "12345678", "photo": "U3dhZ2dlciByb2Nrcw=="}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     */
    public function postProfile(Request $request): JsonResponse
    {
        $dados = $request->all();

        $user = User::findOrFail(Auth::user()->id);
        $dados['uuid'] = Str::uuid();

        if (isset($dados['photo'])) {
            $data = explode(',', $dados['photo']);
            $folder = 'users/';
            $name = $folder . $dados['uuid'] . '.jpg';
            Storage::disk('s3')->put($name, base64_decode($data[0]));
            $url = Storage::disk('s3')->url($name);
            $dados['photo'] = $url;
        }

        $user->update($dados);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'user' => $user
        ]);
    }
}
