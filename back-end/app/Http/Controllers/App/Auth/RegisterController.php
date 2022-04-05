<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/register",
     *      summary="Cadastro de Usuários",
     *      description="Cadastro de usuários",
     *    @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Nome do usuário",
     *          required=true,
     *          example="Teste",
     *          @OA\Schema(type="string",),
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Email do usuário",
     *          required=true, 
     *          example="teste@gmail.com",
     *          @OA\Schema(type="string",),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="Senha do usuário",
     *          required=true, 
     *          example=12345678,
     *          @OA\Schema(type="string",),
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          in="query",
     *          description="Telefone do usuário",
     *          required=true, 
     *          example=11995052373,
     *          @OA\Schema(type="string",),
     *      ),
     *      @OA\Parameter(
     *          name="photo",
     *          in="query",
     *          description="Foto do usuário",
     *          required=true, 
     *          @OA\Schema(type="string",),
     *      ),
     *      @OA\RequestBody(@OA\MediaType(mediaType="application/json",)),
     *      @OA\Response(response=200,description="Successful operation",),
     *      @OA\Response(response=400,description="Bad Request"),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all()[0]]);
        } else {
            $dados = $request->all();

            $dados['uuid'] = Str::uuid();
            $dados['password'] = Hash::make($dados['password']);

            $data = explode(',', $dados['photo']);
            $folder = 'users/';
            $name = $folder . $dados['uuid'] . '.jpg';
            Storage::disk('s3')->put($name, base64_decode($data[0]));
            $url = Storage::disk('s3')->url($name);
            $dados['photo'] = $url;
        }

        $user = User::create($dados);

        return response()->json([
            'status' => true,
            'message' => 'Cadastro efetuado com sucesso!',
            'user' => $user
        ]);
    }
}
