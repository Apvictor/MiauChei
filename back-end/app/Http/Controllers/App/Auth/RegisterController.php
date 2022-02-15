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
