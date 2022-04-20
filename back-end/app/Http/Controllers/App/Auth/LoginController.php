<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/login",
     *      summary="Login de Usuário",
     *      description="Retorna usuário logado",
     *      @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *                 @OA\Property(property="device_name", type="string"),
     *                 example={"email": "armandinho14.ap@gmail.com", "password": "12345678", "device_name": "Swagger"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     * )
     * @throws ValidationException
     */
    public function login(Request $request): array
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 401);
        }


        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => "As credenciais de login são inválidas."], 401);
        }

        $token = auth()->user()->createToken($request->get('device_name'));

        $response = [
            'status' => true,
            'authorization' => $token->plainTextToken,
            'success' => 'Login efetuado com sucesso!',
            'user' => Auth::user()
        ];

        return $response;
    }


    /**
     * Redirecione o usuário para a página de autenticação do provedor.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider = 'facebook')
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtenha as informações do usuário do Provedor.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider = 'facebook')
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Credenciais inválidas fornecidas.'], 422);
        }

        $userCreated = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'photo' => $user->getAvatar(),
            ]
        );

        $userCreated->providers()->updateOrCreate(
            ['provider' => $provider, 'provider_id' => $user->getId(),],
            ['avatar' => $user->getAvatar()]
        );
        $token = $userCreated->createToken('FACEBOOK')->plainTextToken;

        return response()->json($userCreated, 200, ['Access-Token' => $token]);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider = 'facebook')
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return response()->json(['error' => 'Por favor, faça o login usando facebook, github ou google'], 422);
        }
    }
}
