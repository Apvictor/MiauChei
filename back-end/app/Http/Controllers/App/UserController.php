<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
     * @return user
     */
    public function getProfile()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    public function postProfile(Request $request)
    {
        $dados = $request->all();

        $user = User::findOrFail(Auth::user()->id);

        if (isset($dados['photo'])) {
            $data = explode(',', $dados['photo']);
            $folder = 'users/';
            $name = $folder . $user->uuid . '.jpg';
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
