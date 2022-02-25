<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
